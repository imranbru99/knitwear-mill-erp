<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GrnLine extends FactoryRecord
{
    protected $table = 'grn_lines';

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'qty' => 'decimal:3',
            'gross_weight' => 'decimal:3',
            'net_weight' => 'decimal:3',
            'moisture' => 'decimal:3',
        ]);
    }

    public function grn(): BelongsTo
    {
        return $this->belongsTo(Grn::class, 'grn_id');
    }

    public function purchaseOrderLine(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrderLine::class, 'purchase_order_line_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function yarnInspections(): HasMany
    {
        return $this->hasMany(YarnInspection::class, 'grn_line_id');
    }
}
