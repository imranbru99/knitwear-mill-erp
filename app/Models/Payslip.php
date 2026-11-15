<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payslip extends FactoryRecord
{
    protected $table = 'payslips';

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'present_days' => 'decimal:3',
            'ot_hours' => 'decimal:3',
            'gross' => 'decimal:3',
            'deductions' => 'decimal:3',
            'net' => 'decimal:3',
        ]);
    }

    public function payrollRun(): BelongsTo
    {
        return $this->belongsTo(PayrollRun::class, 'payroll_run_id');
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
