<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rfq extends FactoryRecord
{
    protected $table = 'rfqs';

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'due_on' => 'date',
        ]);
    }

    public function requisition(): BelongsTo
    {
        return $this->belongsTo(Requisition::class, 'requisition_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function rfqQuotes(): HasMany
    {
        return $this->hasMany(RfqQuote::class, 'rfq_id');
    }
}
