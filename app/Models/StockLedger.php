<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StockLedger extends AppendOnlyRecord
{
    protected $table = 'stock_ledgers';

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'qty_in' => 'decimal:3',
            'qty_out' => 'decimal:3',
            'happened_at' => 'datetime',
        ]);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}
