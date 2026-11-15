<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Voucher extends FactoryRecord
{
    protected $table = 'vouchers';

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'voucher_date' => 'date',
            'total' => 'decimal:3',
        ]);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function voucherLines(): HasMany
    {
        return $this->hasMany(VoucherLine::class, 'voucher_id');
    }
}
