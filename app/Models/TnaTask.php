<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TnaTask extends FactoryRecord
{
    protected $table = 'tna_tasks';

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'planned_start' => 'date',
            'planned_end' => 'date',
            'actual_end' => 'date',
            'baseline_end' => 'date',
        ]);
    }

    public function productionOrder(): BelongsTo
    {
        return $this->belongsTo(ProductionOrder::class, 'production_order_id');
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'owner_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}
