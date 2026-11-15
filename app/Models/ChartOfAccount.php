<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChartOfAccount extends FactoryRecord
{
    protected $table = 'chart_of_accounts';

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'is_active' => 'boolean',
        ]);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function postingRules(): HasMany
    {
        return $this->hasMany(PostingRule::class, 'debit_account_id');
    }

    public function voucherLines(): HasMany
    {
        return $this->hasMany(VoucherLine::class, 'account_id');
    }
}
