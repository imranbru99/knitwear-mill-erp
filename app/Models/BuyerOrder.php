<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BuyerOrder extends FactoryRecord
{
    protected $table = 'buyer_orders';

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'ex_factory_date' => 'date',
            'ship_date' => 'date',
            'unit_price' => 'decimal:3',
        ]);
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class, 'buyer_id');
    }

    public function style(): BelongsTo
    {
        return $this->belongsTo(Style::class, 'style_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function buyerOrderSizes(): HasMany
    {
        return $this->hasMany(BuyerOrderSize::class, 'buyer_order_id');
    }

    public function productionOrders(): HasMany
    {
        return $this->hasMany(ProductionOrder::class, 'buyer_order_id');
    }

    public function shipments(): HasMany
    {
        return $this->hasMany(Shipment::class, 'buyer_order_id');
    }
}
