<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PurchaseOrder extends FactoryRecord
{
    protected $table = 'purchase_orders';

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'total' => 'decimal:3',
            'expected_on' => 'date',
        ]);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function requisition(): BelongsTo
    {
        return $this->belongsTo(Requisition::class, 'requisition_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function purchaseOrderLines(): HasMany
    {
        return $this->hasMany(PurchaseOrderLine::class, 'purchase_order_id');
    }

    public function grns(): HasMany
    {
        return $this->hasMany(Grn::class, 'purchase_order_id');
    }

    public function supplierBills(): HasMany
    {
        return $this->hasMany(SupplierBill::class, 'purchase_order_id');
    }
}
