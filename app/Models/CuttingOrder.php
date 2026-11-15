<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CuttingOrder extends FactoryRecord
{
    protected $table = 'cutting_orders';

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'waste_kg' => 'decimal:3',
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
