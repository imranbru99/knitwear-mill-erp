<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends FactoryRecord
{
    protected $table = 'vehicles';

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'in_at' => 'datetime',
            'out_at' => 'datetime',
        ]);
    }

    public function gatePass(): BelongsTo
    {
        return $this->belongsTo(GatePass::class, 'gate_pass_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}
