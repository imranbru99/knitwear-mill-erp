<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Size extends FactoryRecord
{
    protected $table = 'sizes';

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}
