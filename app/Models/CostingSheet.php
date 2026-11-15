<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CostingSheet extends FactoryRecord
{
    protected $table = 'costing_sheets';

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'yarn_cost' => 'decimal:3',
            'knitting_cost' => 'decimal:3',
            'linking_cost' => 'decimal:3',
            'mending_cost' => 'decimal:3',
            'washing_cost' => 'decimal:3',
            'finishing_cost' => 'decimal:3',
            'trims_cost' => 'decimal:3',
            'packing_cost' => 'decimal:3',
            'testing_cost' => 'decimal:3',
            'freight' => 'decimal:3',
            'cm' => 'decimal:3',
            'overhead' => 'decimal:3',
            'finance_cost' => 'decimal:3',
            'commission' => 'decimal:3',
            'profit_percent' => 'decimal:3',
            'total_cost' => 'decimal:3',
            'quoted_price' => 'decimal:3',
            'target_price' => 'decimal:3',
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

    public function costingLines(): HasMany
    {
        return $this->hasMany(CostingLine::class, 'costing_sheet_id');
    }
}
