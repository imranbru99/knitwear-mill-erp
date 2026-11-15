<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CostCentre extends FactoryRecord
{
    protected $table = 'cost_centres';

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function voucherLines(): HasMany
    {
        return $this->hasMany(VoucherLine::class, 'cost_centre_id');
    }
}
