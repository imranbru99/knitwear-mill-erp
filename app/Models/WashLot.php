<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WashLot extends FactoryRecord
{
    protected $table = 'wash_lots';

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'shrinkage_percent' => 'decimal:3',
            'handfeel_ok' => 'boolean',
        ]);
    }

    public function washRecipe(): BelongsTo
    {
        return $this->belongsTo(WashRecipe::class, 'wash_recipe_id');
    }

    public function productionOrder(): BelongsTo
    {
        return $this->belongsTo(ProductionOrder::class, 'production_order_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function labTests(): HasMany
    {
        return $this->hasMany(LabTest::class, 'wash_lot_id');
    }
}
