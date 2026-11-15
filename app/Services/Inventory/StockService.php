<?php

namespace App\Services\Inventory;

use App\Models\StockBalance;
use App\Models\StockLedger;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class StockService
{
    /**
     * Append-only stock movement. Balances are a snapshot rebuilt from the ledger.
     *
     * @param  array<string, mixed>  $move
     */
    public function move(array $move): StockLedger
    {
        $in = (float) ($move['qty_in'] ?? 0);
        $out = (float) ($move['qty_out'] ?? 0);
        if ($in < 0 || $out < 0 || ($in == 0.0 && $out == 0.0)) {
            throw new RuntimeException('A stock movement needs a positive quantity in or out.');
        }

        return DB::transaction(function () use ($move, $in, $out): StockLedger {
            $lot = (string) ($move['lot_no'] ?? '');
            $balance = StockBalance::query()
                ->where('branch_id', $move['branch_id'] ?? null)
                ->where('item_type', $move['item_type'])
                ->where('item_code', $move['item_code'])
                ->where('lot_no', $lot)
                ->where('location_id', $move['location_id'] ?? null)
                ->lockForUpdate()
                ->first();

            if (! $balance) {
                $balance = StockBalance::query()->create([
                    'branch_id' => $move['branch_id'] ?? null,
                    'item_type' => $move['item_type'],
                    'item_code' => $move['item_code'],
                    'lot_no' => $lot,
                    'location_id' => $move['location_id'] ?? null,
                    'qty' => 0,
                    'uom' => $move['uom'],
                ]);
            }

            $next = round((float) $balance->qty + $in - $out, 3);
            if ($next < -0.0001) {
                throw new RuntimeException('This issue would take '.$move['item_code'].' lot '.$lot.' below zero.');
            }

            $ledger = StockLedger::query()->create([
                'branch_id' => $move['branch_id'] ?? null,
                'item_type' => $move['item_type'],
                'item_code' => $move['item_code'],
                'lot_no' => $lot !== '' ? $lot : null,
                'location_id' => $move['location_id'] ?? null,
                'qty_in' => $in,
                'qty_out' => $out,
                'uom' => $move['uom'],
                'ref_type' => $move['ref_type'] ?? null,
                'ref_id' => $move['ref_id'] ?? null,
                'happened_at' => $move['happened_at'] ?? now(),
                'note' => $move['note'] ?? null,
            ]);

            $balance->qty = $next;
            $balance->save();

            return $ledger;
        });
    }

    public function onHand(string $itemType, string $itemCode, ?int $branchId = null): float
    {
        return (float) StockBalance::query()
            ->where('item_type', $itemType)
            ->where('item_code', $itemCode)
            ->when($branchId, fn ($q) => $q->where('branch_id', $branchId))
            ->sum('qty');
    }
}
