<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Grn extends FactoryRecord
{
    protected $table = 'grns';

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'received_on' => 'date',
        ]);
    }

    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function grnLines(): HasMany
    {
        return $this->hasMany(GrnLine::class, 'grn_id');
    }

    public function supplierBills(): HasMany
    {
        return $this->hasMany(SupplierBill::class, 'grn_id');
    }
}
