<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExchangeRate extends FactoryRecord
{
    protected $table = 'exchange_rates';

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'rate_date' => 'date',
            'rate_to_bdt' => 'decimal:3',
        ]);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}
