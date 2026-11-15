<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Accident extends FactoryRecord
{
    protected $table = 'accidents';

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'happened_on' => 'date',
        ]);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}
