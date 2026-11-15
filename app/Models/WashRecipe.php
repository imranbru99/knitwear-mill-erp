<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WashRecipe extends FactoryRecord
{
    protected $table = 'wash_recipes';

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'softener_g' => 'decimal:3',
            'enzyme_g' => 'decimal:3',
            'silicon_g' => 'decimal:3',
            'water_l' => 'decimal:3',
            'temp_c' => 'decimal:3',
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

    public function washLots(): HasMany
    {
        return $this->hasMany(WashLot::class, 'wash_recipe_id');
    }
}
