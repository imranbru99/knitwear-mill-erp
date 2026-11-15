<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shipment extends FactoryRecord
{
    protected $table = 'shipments';

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'etd' => 'date',
            'eta' => 'date',
            'cbm' => 'decimal:3',
            'gross_kg' => 'decimal:3',
        ]);
    }

    public function buyerOrder(): BelongsTo
    {
        return $this->belongsTo(BuyerOrder::class, 'buyer_order_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function packingLists(): HasMany
    {
        return $this->hasMany(PackingList::class, 'shipment_id');
    }
}
