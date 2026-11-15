<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GatePass extends FactoryRecord
{
    protected $table = 'gate_passes';

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'returnable' => 'boolean',
        ]);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class, 'gate_pass_id');
    }
}
