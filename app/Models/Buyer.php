<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Buyer extends FactoryRecord
{
    protected $table = 'buyers';

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'default_aql_major' => 'decimal:3',
            'default_aql_minor' => 'decimal:3',
            'credit_limit' => 'decimal:3',
        ]);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function buyerContacts(): HasMany
    {
        return $this->hasMany(BuyerContact::class, 'buyer_id');
    }

    public function buyerVisits(): HasMany
    {
        return $this->hasMany(BuyerVisit::class, 'buyer_id');
    }

    public function inquiries(): HasMany
    {
        return $this->hasMany(Inquiry::class, 'buyer_id');
    }

    public function quotations(): HasMany
    {
        return $this->hasMany(Quotation::class, 'buyer_id');
    }

    public function styles(): HasMany
    {
        return $this->hasMany(Style::class, 'buyer_id');
    }

    public function sampleOrders(): HasMany
    {
        return $this->hasMany(SampleOrder::class, 'buyer_id');
    }

    public function buyerOrders(): HasMany
    {
        return $this->hasMany(BuyerOrder::class, 'buyer_id');
    }

    public function tnaTemplates(): HasMany
    {
        return $this->hasMany(TnaTemplate::class, 'buyer_id');
    }

    public function exportLcs(): HasMany
    {
        return $this->hasMany(ExportLc::class, 'buyer_id');
    }
}
