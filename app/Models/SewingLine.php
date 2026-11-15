<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SewingLine extends FactoryRecord
{
    protected $table = 'sewing_lines';

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'target_efficiency' => 'decimal:3',
        ]);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function hourlyProductions(): HasMany
    {
        return $this->hasMany(HourlyProduction::class, 'sewing_line_id');
    }
}
