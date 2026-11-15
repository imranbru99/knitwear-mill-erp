<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SampleOrder extends FactoryRecord
{
    protected $table = 'sample_orders';

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'requested_on' => 'date',
            'promised_on' => 'date',
            'yarn_grams' => 'decimal:3',
            'cost' => 'decimal:3',
        ]);
    }

    public function style(): BelongsTo
    {
        return $this->belongsTo(Style::class, 'style_id');
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class, 'buyer_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function sampleStages(): HasMany
    {
        return $this->hasMany(SampleStage::class, 'sample_order_id');
    }

    public function sampleComments(): HasMany
    {
        return $this->hasMany(SampleComment::class, 'sample_order_id');
    }
}
