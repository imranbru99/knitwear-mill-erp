<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Carton extends FactoryRecord
{
    protected $table = 'cartons';

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'length_cm' => 'decimal:3',
            'width_cm' => 'decimal:3',
            'height_cm' => 'decimal:3',
            'gross_kg' => 'decimal:3',
            'net_kg' => 'decimal:3',
        ]);
    }

    public function packingList(): BelongsTo
    {
        return $this->belongsTo(PackingList::class, 'packing_list_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}
