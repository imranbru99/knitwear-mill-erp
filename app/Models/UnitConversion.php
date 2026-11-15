<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UnitConversion extends FactoryRecord
{
    protected $table = 'unit_conversions';

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'factor' => 'decimal:3',
        ]);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}
