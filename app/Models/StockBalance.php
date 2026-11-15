<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StockBalance extends AppendOnlyRecord
{
    protected $table = 'stock_balances';

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'qty' => 'decimal:3',
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
