<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LeaveRequest extends FactoryRecord
{
    protected $table = 'leave_requests';

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'starts_on' => 'date',
            'ends_on' => 'date',
            'days' => 'decimal:3',
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
