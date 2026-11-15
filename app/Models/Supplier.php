<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends FactoryRecord
{
    protected $table = 'suppliers';

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'rating' => 'decimal:3',
            'approved' => 'boolean',
        ]);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function issues(): HasMany
    {
        return $this->hasMany(Issue::class, 'supplier_id');
    }

    public function rfqQuotes(): HasMany
    {
        return $this->hasMany(RfqQuote::class, 'supplier_id');
    }

    public function purchaseOrders(): HasMany
    {
        return $this->hasMany(PurchaseOrder::class, 'supplier_id');
    }

    public function grns(): HasMany
    {
        return $this->hasMany(Grn::class, 'supplier_id');
    }

    public function dyeingChallans(): HasMany
    {
        return $this->hasMany(DyeingChallan::class, 'supplier_id');
    }

    public function supplierBills(): HasMany
    {
        return $this->hasMany(SupplierBill::class, 'supplier_id');
    }

    public function btbLcs(): HasMany
    {
        return $this->hasMany(BtbLc::class, 'supplier_id');
    }
}
