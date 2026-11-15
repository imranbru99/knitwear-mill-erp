<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UnitOfMeasure extends FactoryRecord
{
    protected $table = 'units_of_measure';

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}
