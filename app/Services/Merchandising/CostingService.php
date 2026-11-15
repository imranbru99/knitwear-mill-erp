<?php

namespace App\Services\Merchandising;

use App\Models\CostingSheet;

class CostingService
{
    public function calculate(CostingSheet $sheet): CostingSheet
    {
        $sheet->load('costingLines');
        $fromLines = round((float) $sheet->costingLines->sum('amount'), 2);
        $header = (float) $sheet->yarn_cost
            + (float) $sheet->knitting_cost
            + (float) $sheet->linking_cost
            + (float) $sheet->mending_cost
            + (float) $sheet->washing_cost
            + (float) $sheet->finishing_cost
            + (float) $sheet->trims_cost
            + (float) $sheet->packing_cost
            + (float) $sheet->testing_cost
            + (float) $sheet->freight
            + (float) $sheet->cm
            + (float) $sheet->overhead
            + (float) $sheet->finance_cost
            + (float) $sheet->commission;

        $total = $fromLines > 0 ? $fromLines : round($header, 2);
        $profit = (float) $sheet->profit_percent;
        $quoted = round($total * (1 + ($profit / 100)), 2);

        $sheet->update([
            'total_cost' => $total,
            'quoted_price' => $quoted,
        ]);

        return $sheet->fresh();
    }

    /**
     * Yarn cost per piece from grams, rate per kg and the two wastage steps a sweater actually loses.
     */
    public function yarnCostPerPiece(float $grams, float $ratePerKg, float $knitWastePercent, float $linkWastePercent): float
    {
        $kg = $grams / 1000;

        return round($kg * $ratePerKg * (1 + $knitWastePercent / 100) * (1 + $linkWastePercent / 100), 4);
    }
}
