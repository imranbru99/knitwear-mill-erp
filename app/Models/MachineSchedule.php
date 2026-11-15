<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MachineSchedule extends FactoryRecord
{
    protected $table = 'machine_schedules';

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
        ]);
    }

    public function machine(): BelongsTo
    {
        return $this->belongsTo(Machine::class, 'machine_id');
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
