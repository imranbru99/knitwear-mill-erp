<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class YarnCount extends FactoryRecord
{
    protected $table = 'yarn_counts';

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}
