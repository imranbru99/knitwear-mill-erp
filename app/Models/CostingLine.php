<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CostingLine extends FactoryRecord
{
    protected $table = 'costing_lines';

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'qty' => 'decimal:3',
            'rate' => 'decimal:3',
            'wastage_percent' => 'decimal:3',
            'amount' => 'decimal:3',
        ]);
    }

    public function costingSheet(): BelongsTo
    {
        return $this->belongsTo(CostingSheet::class, 'costing_sheet_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}
