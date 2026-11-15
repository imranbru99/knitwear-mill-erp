<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StyleMeasurement extends FactoryRecord
{
    protected $table = 'style_measurements';

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'spec_cm' => 'decimal:3',
            'tolerance_plus' => 'decimal:3',
            'tolerance_minus' => 'decimal:3',
        ]);
    }

    public function style(): BelongsTo
    {
        return $this->belongsTo(Style::class, 'style_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}
