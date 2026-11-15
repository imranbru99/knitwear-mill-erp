<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bundle extends FactoryRecord
{
    protected $table = 'bundles';

    public function productionOrder(): BelongsTo
    {
        return $this->belongsTo(ProductionOrder::class, 'production_order_id');
    }

    public function style(): BelongsTo
    {
        return $this->belongsTo(Style::class, 'style_id');
    }

    public function machine(): BelongsTo
    {
        return $this->belongsTo(Machine::class, 'machine_id');
    }

    public function operator(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'operator_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function productionEntries(): HasMany
    {
        return $this->hasMany(ProductionEntry::class, 'bundle_id');
    }

    public function wipScans(): HasMany
    {
        return $this->hasMany(WipScan::class, 'bundle_id');
    }
}
