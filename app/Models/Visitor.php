<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Visitor extends FactoryRecord
{
    protected $table = 'visitors';

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'in_at' => 'datetime',
            'out_at' => 'datetime',
        ]);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}
