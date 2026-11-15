<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UtilityReading extends FactoryRecord
{
    protected $table = 'utility_readings';

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'reading_on' => 'date',
            'quantity' => 'decimal:3',
        ]);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}
