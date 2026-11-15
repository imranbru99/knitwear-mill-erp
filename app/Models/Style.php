<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Style extends FactoryRecord
{
    protected $table = 'styles';

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'gsm' => 'decimal:3',
            'dpp' => 'array',
        ]);
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class, 'buyer_id');
    }

    public function productCategory(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function quotations(): HasMany
    {
        return $this->hasMany(Quotation::class, 'style_id');
    }

    public function styleColourways(): HasMany
    {
        return $this->hasMany(StyleColourway::class, 'style_id');
    }

    public function styleMeasurements(): HasMany
    {
        return $this->hasMany(StyleMeasurement::class, 'style_id');
    }

    public function techPackVersions(): HasMany
    {
        return $this->hasMany(TechPackVersion::class, 'style_id');
    }

    public function sampleOrders(): HasMany
    {
        return $this->hasMany(SampleOrder::class, 'style_id');
    }

    public function costingSheets(): HasMany
    {
        return $this->hasMany(CostingSheet::class, 'style_id');
    }

    public function buyerOrders(): HasMany
    {
        return $this->hasMany(BuyerOrder::class, 'style_id');
    }

    public function boms(): HasMany
    {
        return $this->hasMany(Bom::class, 'style_id');
    }

    public function knitPrograms(): HasMany
    {
        return $this->hasMany(KnitProgram::class, 'style_id');
    }

    public function bundles(): HasMany
    {
        return $this->hasMany(Bundle::class, 'style_id');
    }

    public function washRecipes(): HasMany
    {
        return $this->hasMany(WashRecipe::class, 'style_id');
    }

    public function labTests(): HasMany
    {
        return $this->hasMany(LabTest::class, 'style_id');
    }

    public function operationBreakdowns(): HasMany
    {
        return $this->hasMany(OperationBreakdown::class, 'style_id');
    }
}
