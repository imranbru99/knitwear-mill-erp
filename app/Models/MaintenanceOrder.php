<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MaintenanceOrder extends FactoryRecord
{
    protected $table = 'maintenance_orders';

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'started_at' => 'datetime',
            'finished_at' => 'datetime',
            'cost' => 'decimal:3',
        ]);
    }

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}
