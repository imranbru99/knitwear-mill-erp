<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Inquiry extends FactoryRecord
{
    protected $table = 'inquiries';

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'target_price' => 'decimal:3',
            'acknowledged_at' => 'datetime',
        ]);
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class, 'buyer_id');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'assigned_to');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function quotations(): HasMany
    {
        return $this->hasMany(Quotation::class, 'inquiry_id');
    }
}
