<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductCategory extends FactoryRecord
{
    protected $table = 'product_categories';

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function styles(): HasMany
    {
        return $this->hasMany(Style::class, 'product_category_id');
    }
}
