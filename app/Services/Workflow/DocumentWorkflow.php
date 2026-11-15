<?php

namespace App\Services\Workflow;

use App\Models\BuyerOrder;
use App\Models\CostingSheet;
use App\Models\Grn;
use App\Models\GrnLine;
use App\Models\Inspection;
use App\Models\Issue;
use App\Models\PayrollRun;
use App\Models\ProductionOrder;
use App\Models\PurchaseOrder;
use App\Models\Requisition;
use App\Models\Voucher;
use App\Models\YarnInspection;
use App\Services\Accounts\PostingService;
use App\Services\Hr\PayrollService;
use App\Services\Inventory\StockService;
use App\Services\Issues\IssueEngine;
use App\Services\Merchandising\CostingService;
use App\Services\Merchandising\MrpService;
use App\Services\Merchandising\TnaService;
use App\Services\Quality\AqlService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DocumentWorkflow
{
    public function __construct(
        private IssueEngine $issues,
        private TnaService $tna,
        private MrpService $mrp,
        private CostingService $costing,
        private StockService $stock,
        private AqlService $aql,
        private PostingService $posting,
        private PayrollService $payroll,
    ) {}

    public function raise(Model $record): Issue
    {
        return $this->issues->raiseFromDocument($record, 'manual', 'Raised from '.$record->getTable().' #'.$record->getKey());
    }

    public function close(Model $record): Issue
    {
        if (! $record instanceof Issue) {
            return $this->raise($record);
        }

        return $this->issues->close($record, [
            'root_cause' => $record->root_cause,
            'corrective_action' => $record->corrective_action,
            'preventive_action' => $record->preventive_action,
        ]);
    }

    public function confirm(Model $record): ProductionOrder
    {
        /** @var BuyerOrder $record */
        return DB::transaction(function () use ($record): ProductionOrder {
            $number = $record->number;
            if ($number === 'BPO-DRAFT' || blank($number) || str_starts_with((string) $number, 'BPO-DRAFT')) {
                $number = app(\App\Services\Documents\NumberingService::class)->next('buyer_order', $record->branch_id);
            }
            $record->update(['status' => 'confirmed', 'number' => $number]);
            $job = ProductionOrder::query()->firstOrCreate(
                ['buyer_order_id' => $record->id],
                [
                    'number' => app(\App\Services\Documents\NumberingService::class)->next('production_order', $record->branch_id),
                    'route' => $record->production_route,
                    'quantity' => $record->quantity,
                    'status' => 'planned',
                    'unit_name' => $record->production_route === 'sweater' ? 'Unit-1 Knitting' : 'Unit-2 Sewing',
                    'branch_id' => $record->branch_id,
                ],
            );
            $this->tna->generate($job);

            return $job;
        });
    }

    public function approve(Model $record): Model
    {
        if ($record instanceof CostingSheet) {
            $this->costing->calculate($record);
            $record->update(['status' => 'approved']);

            return $record;
        }

        $record->update(['status' => 'approved']);

        return $record;
    }

    public function mrp(Model $record): void
    {
        if ($record instanceof ProductionOrder) {
            $this->mrp->explode($record);
        }
    }

    public function post(Model $record): Model
    {
        if ($record instanceof Grn) {
            return $this->postGrn($record);
        }
        if ($record instanceof Voucher && $record->status !== 'posted') {
            $record->update(['status' => 'posted']);
        }

        return $record;
    }

    public function judge(Model $record): Inspection
    {
        /** @var Inspection $record */
        $judgement = $this->aql->judge(
            (int) $record->lot_size,
            (float) $record->aql_major,
            (float) $record->aql_minor,
            (int) $record->major_found,
            (int) $record->minor_found,
            (int) $record->critical_found,
        );
        $record->update([
            'sample_size' => $judgement['sample_size'],
            'major_accept' => $judgement['major_accept'],
            'result' => $judgement['result'],
            'status' => 'closed',
        ]);
        if ($judgement['result'] === 'fail') {
            $record->productionOrder?->update(['risk' => 'red', 'status' => 'held']);
            $this->issues->raiseFromDocument($record, 'inspection_fail', 'Inspection failed '.$record->number, [
                'production_order_id' => $record->production_order_id,
            ]);
        }

        return $record->fresh();
    }

    public function run(Model $record): Model
    {
        if ($record instanceof PayrollRun) {
            return $this->payroll->calculate($record);
        }

        return $record;
    }

    private function postGrn(Grn $grn): Grn
    {
        if ($grn->status === 'posted') {
            return $grn;
        }

        DB::transaction(function () use ($grn): void {
            $grn->load('grnLines.yarnInspections');
            foreach ($grn->grnLines as $line) {
                $decision = $this->decision($line);
                if ($decision === 'reject') {
                    $line->update(['inspection_status' => 'reject']);
                    $this->issues->raise([
                        'type_code' => 'shade_mismatch',
                        'title' => 'Yarn lot '.$line->lot_no.' rejected at incoming inspection',
                        'description' => 'Shade or count failed on challan '.$grn->challan_no.'.',
                        'source_type' => $line->getMorphClass(),
                        'source_id' => $line->id,
                        'supplier_id' => $grn->supplier_id,
                        'yarn_lot' => $line->lot_no,
                        'branch_id' => $grn->branch_id,
                    ]);
                    continue;
                }
                $this->stock->move([
                    'branch_id' => $grn->branch_id,
                    'item_type' => 'yarn',
                    'item_code' => $line->item_code,
                    'lot_no' => $line->lot_no,
                    'qty_in' => $line->net_weight ?: $line->qty,
                    'uom' => $line->uom,
                    'ref_type' => $grn->getMorphClass(),
                    'ref_id' => $grn->id,
                    'note' => 'GRN '.$grn->number,
                ]);
                $line->update(['inspection_status' => $decision === 'hold' ? 'hold' : 'pass']);
            }
            $grn->update(['status' => 'posted']);
            $amount = (float) $grn->purchaseOrder?->total;
            if ($amount > 0) {
                $this->posting->post('grn', $amount, 'grn:'.$grn->id, [
                    'branch_id' => $grn->branch_id,
                    'narration' => 'Yarn received '.$grn->number,
                ]);
            }
        });

        return $grn->fresh();
    }

    private function decision(GrnLine $line): string
    {
        /** @var YarnInspection|null $inspection */
        $inspection = $line->yarnInspections->sortByDesc('id')->first();

        return $inspection->decision ?? 'pass';
    }
}
