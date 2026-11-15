<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Holiday extends FactoryRecord
{
    protected $table = 'holidays';

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'holiday_date' => 'date',
        ]);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}
