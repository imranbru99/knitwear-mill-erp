<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FabricRoll extends FactoryRecord
{
    protected $table = 'fabric_rolls';

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'dia_inch' => 'decimal:3',
            'gsm' => 'decimal:3',
            'length_m' => 'decimal:3',
            'weight_kg' => 'decimal:3',
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
