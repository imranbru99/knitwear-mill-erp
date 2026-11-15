<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Machine extends FactoryRecord
{
    protected $table = 'machines';

    public function operator(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'operator_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function issues(): HasMany
    {
        return $this->hasMany(Issue::class, 'machine_id');
    }

    public function machineSchedules(): HasMany
    {
        return $this->hasMany(MachineSchedule::class, 'machine_id');
    }

    public function bundles(): HasMany
    {
        return $this->hasMany(Bundle::class, 'machine_id');
    }

    public function productionEntries(): HasMany
    {
        return $this->hasMany(ProductionEntry::class, 'machine_id');
    }

    public function downtimeLogs(): HasMany
    {
        return $this->hasMany(DowntimeLog::class, 'machine_id');
    }

    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class, 'machine_id');
    }
}
