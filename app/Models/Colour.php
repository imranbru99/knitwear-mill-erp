<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Colour extends FactoryRecord
{
    protected $table = 'colours';

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function styleColourways(): HasMany
    {
        return $this->hasMany(StyleColourway::class, 'colour_id');
    }
}
