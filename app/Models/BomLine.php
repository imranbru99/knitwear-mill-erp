<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BomLine extends FactoryRecord
{
    protected $table = 'bom_lines';

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'qty_per_piece' => 'decimal:3',
            'wastage_percent' => 'decimal:3',
        ]);
    }

    public function bom(): BelongsTo
    {
        return $this->belongsTo(Bom::class, 'bom_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}
