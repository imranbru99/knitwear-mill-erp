<?php

namespace App\Services\Documents;

use App\Models\NumberingSequence;
use App\Support\Integrations\FactoryCalendar;
use Illuminate\Support\Facades\DB;

class NumberingService
{
    public function __construct(private FactoryCalendar $calendar) {}

    public function next(string $docType, ?int $branchId = null, ?string $prefix = null): string
    {
        $prefix ??= $this->prefix($docType);
        $year = $this->calendar->financialYear();

        return DB::transaction(function () use ($docType, $branchId, $prefix, $year): string {
            $sequence = NumberingSequence::query()->firstOrCreate(
                [
                    'branch_id' => $branchId,
                    'doc_type' => $docType,
                    'financial_year' => $year,
                ],
                ['prefix' => $prefix, 'last_number' => 0, 'padding' => 5],
            );

            $locked = NumberingSequence::query()->whereKey($sequence->id)->lockForUpdate()->firstOrFail();
            $locked->last_number = (int) $locked->last_number + 1;
            $locked->prefix = $prefix;
            $locked->save();

            return sprintf('%s-%s-%s', $prefix, $year, str_pad((string) $locked->last_number, (int) $locked->padding, '0', STR_PAD_LEFT));
        });
    }

    public function prefix(string $docType): string
    {
        return match ($docType) {
            'inquiry' => 'INQ',
            'quotation' => 'QT',
            'sample' => 'SMP',
            'costing' => 'CST',
            'buyer_order' => 'BPO',
            'production_order' => 'JOB',
            'requisition' => 'REQ',
            'purchase_order' => 'YPO',
            'grn' => 'GRN',
            'gate_pass' => 'GP',
            'bundle' => 'BND',
            'inspection' => 'INS',
            'packing_list' => 'PKL',
            'shipment' => 'SHP',
            'export_lc' => 'LC',
            'btb_lc' => 'BTB',
            'voucher' => 'JV',
            'payroll' => 'PR',
            'issue' => 'ISS',
            'dyeing' => 'DYE',
            'maintenance' => 'MO',
            'wash_lot' => 'WL',
            'cutting' => 'CO',
            'rfq' => 'RFQ',
            default => strtoupper(substr($docType, 0, 3)),
        };
    }
}
