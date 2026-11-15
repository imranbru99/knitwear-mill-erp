<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PurchaseOrderLine extends FactoryRecord
{
    protected $table = 'purchase_order_lines';

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'qty' => 'decimal:3',
            'rate' => 'decimal:3',
            'received_qty' => 'decimal:3',
        ]);
    }

    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function grnLines(): HasMany
    {
        return $this->hasMany(GrnLine::class, 'purchase_order_line_id');
    }
}
