<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DowntimeLog extends FactoryRecord
{
    protected $table = 'downtime_logs';

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'started_at' => 'datetime',
            'ended_at' => 'datetime',
        ]);
    }

    public function machine(): BelongsTo
    {
        return $this->belongsTo(Machine::class, 'machine_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}
