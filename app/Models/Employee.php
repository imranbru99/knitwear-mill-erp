<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends FactoryRecord
{
    protected $table = 'employees';

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'join_date' => 'date',
            'basic_salary' => 'decimal:3',
            'piece_rate' => 'decimal:3',
            'bank_account' => 'encrypted',
            'nid' => 'encrypted',
        ]);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function shiftRosters(): HasMany
    {
        return $this->hasMany(ShiftRoster::class, 'employee_id');
    }

    public function leaveRequests(): HasMany
    {
        return $this->hasMany(LeaveRequest::class, 'employee_id');
    }

    public function payslips(): HasMany
    {
        return $this->hasMany(Payslip::class, 'employee_id');
    }

    public function accidents(): HasMany
    {
        return $this->hasMany(Accident::class, 'employee_id');
    }
}
