<?php

namespace App\Services\Issues;

use App\Models\AutomationRule;
use App\Models\DowntimeLog;
use App\Models\ExportLc;
use App\Models\Inquiry;
use App\Models\Licence;
use App\Models\PurchaseOrder;
use App\Models\SampleComment;
use App\Models\TnaTask;

class ExceptionScanner
{
    public function __construct(private IssueEngine $issues) {}

    public function scan(): int
    {
        $raised = 0;
        $raised += $this->inquiries();
        $raised += $this->tna();
        $raised += $this->lettersOfCredit();
        $raised += $this->licences();
        $raised += $this->purchaseOrders();
        $raised += $this->sampleComments();
        $raised += $this->downtime();

        return $raised;
    }

    private function threshold(string $key, string $field, int $default): int
    {
        $rule = AutomationRule::query()->where('key', $key)->first();
        if (! $rule || ! $rule->enabled) {
            return $default;
        }
        $value = $rule->thresholds[$field] ?? $default;

        return (int) $value;
    }

    private function inquiries(): int
    {
        $hours = $this->threshold('inquiry_unanswered', 'hours', 24);
        $count = 0;
        Inquiry::query()->where('status', 'new')->where('created_at', '<', now()->subHours($hours))->each(function (Inquiry $inquiry) use (&$count): void {
            $this->issues->raiseFromDocument($inquiry, 'inquiry_silent', 'Inquiry '.$inquiry->number.' has had no answer');
            $count++;
        });

        return $count;
    }

    private function tna(): int
    {
        $count = 0;
        TnaTask::query()->where('status', 'pending')->whereDate('planned_end', '<', today())->each(function (TnaTask $task) use (&$count): void {
            $task->update(['status' => 'late']);
            $task->productionOrder?->update(['risk' => 'red']);
            $this->issues->raiseFromDocument($task, 'tna_late', 'TNA late: '.$task->name, [
                'production_order_id' => $task->production_order_id,
            ]);
            $count++;
        });

        return $count;
    }

    private function lettersOfCredit(): int
    {
        $days = $this->threshold('lc_expiry', 'days', 15);
        $count = 0;
        ExportLc::query()->where('status', 'open')->whereDate('expiry_on', '<=', today()->addDays($days))->each(function (ExportLc $lc) use (&$count): void {
            $this->issues->raiseFromDocument($lc, 'lc_expiry', 'Export LC '.$lc->number.' is near expiry');
            $count++;
        });

        return $count;
    }

    private function licences(): int
    {
        $count = 0;
        Licence::query()->whereDate('expires_on', '<=', today()->addDays(30))->each(function (Licence $licence) use (&$count): void {
            $this->issues->raiseFromDocument($licence, 'licence_expiry', $licence->name.' expires '.$licence->expires_on?->toDateString());
            $count++;
        });

        return $count;
    }

    private function purchaseOrders(): int
    {
        $count = 0;
        PurchaseOrder::query()->where('status', 'approved')->whereDate('expected_on', '<', today())->each(function (PurchaseOrder $po) use (&$count): void {
            $this->issues->raiseFromDocument($po, 'supplier_late', 'Supplier late on '.$po->number, [
                'supplier_id' => $po->supplier_id,
            ]);
            $count++;
        });

        return $count;
    }

    private function sampleComments(): int
    {
        $count = 0;
        SampleComment::query()->where('status', 'open')->whereDate('due_on', '<', today())->each(function (SampleComment $comment) use (&$count): void {
            $this->issues->raiseFromDocument($comment, 'sample_comment', 'Buyer comment not addressed: '.$comment->point);
            $count++;
        });

        return $count;
    }

    private function downtime(): int
    {
        $minutes = $this->threshold('machine_idle', 'minutes', 30);
        $count = 0;
        DowntimeLog::query()->where('minutes', '>=', $minutes)->each(function (DowntimeLog $log) use (&$count): void {
            $this->issues->raiseFromDocument($log, 'machine_idle', 'Machine idle: '.$log->reason, [
                'machine_id' => $log->machine_id,
            ]);
            $count++;
        });

        return $count;
    }
}
