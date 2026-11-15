<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MaterialRequirement extends FactoryRecord
{
    protected $table = 'material_requirements';

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'required_qty' => 'decimal:3',
            'available_qty' => 'decimal:3',
            'incoming_qty' => 'decimal:3',
            'reserved_qty' => 'decimal:3',
            'shortfall_qty' => 'decimal:3',
        ]);
    }

    public function productionOrder(): BelongsTo
    {
        return $this->belongsTo(ProductionOrder::class, 'production_order_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}
