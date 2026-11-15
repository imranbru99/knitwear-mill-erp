<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Requisition extends FactoryRecord
{
    protected $table = 'requisitions';

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'needed_by' => 'date',
        ]);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function requisitionLines(): HasMany
    {
        return $this->hasMany(RequisitionLine::class, 'requisition_id');
    }

    public function rfqs(): HasMany
    {
        return $this->hasMany(Rfq::class, 'requisition_id');
    }

    public function purchaseOrders(): HasMany
    {
        return $this->hasMany(PurchaseOrder::class, 'requisition_id');
    }
}
