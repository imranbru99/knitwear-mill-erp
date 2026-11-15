<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BuyerContact extends FactoryRecord
{
    protected $table = 'buyer_contacts';

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'is_primary' => 'boolean',
        ]);
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class, 'buyer_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}
