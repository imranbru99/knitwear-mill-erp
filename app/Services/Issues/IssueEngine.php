<?php

namespace App\Services\Issues;

use App\Models\Issue;
use App\Models\IssueAction;
use App\Models\IssueEscalation;
use App\Models\IssueSolution;
use App\Models\IssueType;
use App\Services\Documents\NumberingService;
use App\Support\Integrations\AiClient;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class IssueEngine
{
    public function __construct(
        private NumberingService $numbering,
        private AiClient $ai,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function raise(array $data): Issue
    {
        $type = IssueType::query()->where('code', $data['type_code'])->firstOrFail();

        $existing = Issue::query()
            ->where('issue_type_id', $type->id)
            ->where('status', 'open')
            ->when(isset($data['source_type'], $data['source_id']), function ($query) use ($data) {
                $query->where('source_type', $data['source_type'])->where('source_id', $data['source_id']);
            })
            ->first();

        if ($existing && ($data['idempotent'] ?? true)) {
            return $existing;
        }

        return DB::transaction(function () use ($data, $type): Issue {
            $issue = Issue::query()->create([
                'number' => $this->numbering->next('issue', $data['branch_id'] ?? null),
                'issue_type_id' => $type->id,
                'severity' => $data['severity'] ?? $type->severity,
                'module' => $type->module,
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'source_type' => $data['source_type'] ?? null,
                'source_id' => $data['source_id'] ?? null,
                'production_order_id' => $data['production_order_id'] ?? null,
                'machine_id' => $data['machine_id'] ?? null,
                'supplier_id' => $data['supplier_id'] ?? null,
                'department_id' => $data['department_id'] ?? null,
                'owner_id' => $data['owner_id'] ?? null,
                'yarn_lot' => $data['yarn_lot'] ?? null,
                'defect_code' => $data['defect_code'] ?? null,
                'due_at' => now()->addMinutes((int) $type->sla_minutes),
                'status' => 'open',
                'sla_due_at' => now()->addMinutes((int) $type->sla_minutes),
                'branch_id' => $data['branch_id'] ?? null,
            ]);

            IssueAction::query()->create([
                'issue_id' => $issue->id,
                'user_id' => auth()->id(),
                'action' => 'raised',
                'comment' => $data['description'] ?? $issue->title,
                'to_status' => 'open',
                'branch_id' => $issue->branch_id,
            ]);

            $this->summarise($issue);
            if ($data['detect_chronic'] ?? true) {
                $this->detectChronic($issue->fresh());
            }

            return $issue->fresh(['issueType']);
        });
    }

    public function raiseFromDocument(Model $record, string $typeCode, string $title, array $extra = []): Issue
    {
        return $this->raise(array_merge([
            'type_code' => $typeCode,
            'title' => $title,
            'source_type' => $record->getMorphClass(),
            'source_id' => $record->getKey(),
            'branch_id' => $record->getAttribute('branch_id'),
            'production_order_id' => $record->getAttribute('production_order_id') ?? ($record instanceof \App\Models\ProductionOrder ? $record->id : null),
        ], $extra));
    }

    /**
     * @param  array<string, mixed>  $capa
     */
    public function close(Issue $issue, array $capa): Issue
    {
        foreach (['root_cause', 'corrective_action', 'preventive_action'] as $field) {
            if (blank($capa[$field] ?? $issue->{$field})) {
                throw ValidationException::withMessages([
                    $field => 'Closure needs root cause, corrective action and preventive action.',
                ]);
            }
        }

        $issue->update([
            'root_cause' => $capa['root_cause'] ?? $issue->root_cause,
            'why_1' => $capa['why_1'] ?? $issue->why_1,
            'why_2' => $capa['why_2'] ?? $issue->why_2,
            'why_3' => $capa['why_3'] ?? $issue->why_3,
            'why_4' => $capa['why_4'] ?? $issue->why_4,
            'why_5' => $capa['why_5'] ?? $issue->why_5,
            'corrective_action' => $capa['corrective_action'] ?? $issue->corrective_action,
            'preventive_action' => $capa['preventive_action'] ?? $issue->preventive_action,
            'status' => 'closed',
            'verified_at' => now(),
        ]);

        IssueAction::query()->create([
            'issue_id' => $issue->id,
            'user_id' => auth()->id(),
            'action' => 'closed',
            'from_status' => 'open',
            'to_status' => 'closed',
            'comment' => $issue->root_cause,
            'branch_id' => $issue->branch_id,
        ]);

        return $issue->fresh();
    }

    public function escalateDue(): int
    {
        $count = 0;
        Issue::query()
            ->where('status', 'open')
            ->where('escalation_level', '<', 2)
            ->whereNotNull('sla_due_at')
            ->where('sla_due_at', '<', now())
            ->orderBy('id')
            ->each(function (Issue $issue) use (&$count): void {
                $level = (int) $issue->escalation_level + 1;
                $role = $level === 1 ? 'General Manager' : 'Managing Director';
                $issue->update(['escalation_level' => $level]);
                IssueEscalation::query()->create([
                    'issue_id' => $issue->id,
                    'level' => $level,
                    'escalated_to_role' => $role,
                    'escalated_at' => now(),
                    'note' => 'SLA missed',
                    'branch_id' => $issue->branch_id,
                ]);
                IssueAction::query()->create([
                    'issue_id' => $issue->id,
                    'action' => 'escalated',
                    'comment' => 'Escalated to '.$role,
                    'branch_id' => $issue->branch_id,
                ]);
                $count++;
            });

        return $count;
    }

    public function detectChronic(Issue $issue): ?Issue
    {
        $query = Issue::query()
            ->where('issue_type_id', $issue->issue_type_id)
            ->where('created_at', '>=', now()->subDays(90))
            ->where('id', '!=', $issue->id)
            ->where('chronic', false);

        $matched = false;
        if ($issue->machine_id) {
            $matched = (clone $query)->where('machine_id', $issue->machine_id)->count() >= 2;
        }
        if (! $matched && $issue->supplier_id) {
            $matched = (clone $query)->where('supplier_id', $issue->supplier_id)->count() >= 2;
        }
        if (! $matched && $issue->yarn_lot) {
            $matched = (clone $query)->where('yarn_lot', $issue->yarn_lot)->count() >= 2;
        }
        if (! $matched && $issue->defect_code) {
            $matched = (clone $query)->where('defect_code', $issue->defect_code)->count() >= 2;
        }
        if (! $matched) {
            return null;
        }

        $parent = Issue::query()
            ->where('issue_type_id', $issue->issue_type_id)
            ->where('chronic', true)
            ->where('status', 'open')
            ->when($issue->machine_id, fn ($q) => $q->where('machine_id', $issue->machine_id))
            ->when($issue->supplier_id, fn ($q) => $q->where('supplier_id', $issue->supplier_id))
            ->when($issue->yarn_lot, fn ($q) => $q->where('yarn_lot', $issue->yarn_lot))
            ->first();

        if (! $parent) {
            $parent = $this->raise([
                'type_code' => $issue->issueType->code,
                'title' => 'Chronic: '.$issue->title,
                'description' => 'The same fault has come back on this machine, supplier, lot or defect within 90 days.',
                'severity' => 'critical',
                'machine_id' => $issue->machine_id,
                'supplier_id' => $issue->supplier_id,
                'yarn_lot' => $issue->yarn_lot,
                'defect_code' => $issue->defect_code,
                'branch_id' => $issue->branch_id,
                'idempotent' => false,
                'detect_chronic' => false,
            ]);
            $parent->update(['chronic' => true]);
        }

        $issue->update(['parent_id' => $parent->id]);

        return $parent;
    }

    private function summarise(Issue $issue): void
    {
        $solution = IssueSolution::query()->where('issue_type_id', $issue->issue_type_id)->orderByDesc('times_used')->first();
        $hint = $solution?->body ?? $issue->issueType?->solution_template;
        $summary = $this->ai->text(
            "Summarise this knitwear factory issue and suggest one next action.\nTitle: {$issue->title}\nKnown fix: {$hint}",
            'You are a knitwear factory supervisor. Be concrete and short.',
        );
        $issue->update(['ai_summary' => $summary ?: $hint]);
    }
}
