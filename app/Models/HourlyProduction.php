<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HourlyProduction extends FactoryRecord
{
    protected $table = 'hourly_productions';

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'hour_at' => 'datetime',
            'dhu' => 'decimal:3',
        ]);
    }

    public function sewingLine(): BelongsTo
    {
        return $this->belongsTo(SewingLine::class, 'sewing_line_id');
    }

    public function productionOrder(): BelongsTo
    {
        return $this->belongsTo(ProductionOrder::class, 'production_order_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}
