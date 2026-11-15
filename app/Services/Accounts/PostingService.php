<?php

namespace App\Services\Accounts;

use App\Models\PostingRule;
use App\Models\Voucher;
use App\Models\VoucherLine;
use App\Services\Documents\NumberingService;
use Illuminate\Support\Facades\DB;

class PostingService
{
    public function __construct(private NumberingService $numbering) {}

    /**
     * @param  array<string, mixed>  $meta
     */
    public function post(string $event, float $amount, string $sourceKey, array $meta = []): Voucher
    {
        $existing = Voucher::query()->where('source_key', $sourceKey)->first();
        if ($existing) {
            return $existing;
        }

        $rule = PostingRule::query()->where('event', $event)->where('active', true)->firstOrFail();

        return DB::transaction(function () use ($event, $amount, $sourceKey, $meta, $rule): Voucher {
            $voucher = Voucher::query()->create([
                'number' => $this->numbering->next('voucher', $meta['branch_id'] ?? null),
                'type' => 'journal',
                'voucher_date' => now()->toDateString(),
                'narration' => $meta['narration'] ?? $event,
                'status' => 'posted',
                'total' => round($amount, 2),
                'source_key' => $sourceKey,
                'branch_id' => $meta['branch_id'] ?? null,
            ]);

            VoucherLine::query()->create([
                'voucher_id' => $voucher->id,
                'account_id' => $rule->debit_account_id,
                'debit' => round($amount, 2),
                'credit' => 0,
                'production_order_id' => $meta['production_order_id'] ?? null,
                'branch_id' => $voucher->branch_id,
            ]);
            VoucherLine::query()->create([
                'voucher_id' => $voucher->id,
                'account_id' => $rule->credit_account_id,
                'debit' => 0,
                'credit' => round($amount, 2),
                'production_order_id' => $meta['production_order_id'] ?? null,
                'branch_id' => $voucher->branch_id,
            ]);

            return $voucher;
        });
    }
}
