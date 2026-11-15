<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shift extends FactoryRecord
{
    protected $table = 'shifts';

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'is_night' => 'boolean',
        ]);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function shiftRosters(): HasMany
    {
        return $this->hasMany(ShiftRoster::class, 'shift_id');
    }
}
