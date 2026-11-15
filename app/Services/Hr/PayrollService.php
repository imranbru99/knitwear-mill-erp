<?php

namespace App\Services\Hr;

use App\Models\Employee;
use App\Models\PayrollRun;
use App\Models\Payslip;
use Illuminate\Support\Facades\DB;

class PayrollService
{
    public function calculate(PayrollRun $run): PayrollRun
    {
        $employees = Employee::query()
            ->where('status', 'active')
            ->when($run->branch_id, fn ($q) => $q->where('branch_id', $run->branch_id))
            ->get();

        DB::transaction(function () use ($run, $employees): void {
            foreach ($employees as $employee) {
                $present = (float) ($employee->getAttribute('present_days_override') ?? 26);
                $otHours = (float) ($employee->getAttribute('ot_hours_override') ?? 0);
                $pieceQty = (int) ($employee->getAttribute('piece_qty_override') ?? 0);
                $gross = $this->gross((float) $employee->basic_salary, $present, $otHours, $pieceQty, (float) $employee->piece_rate);
                Payslip::query()->updateOrCreate(
                    ['payroll_run_id' => $run->id, 'employee_id' => $employee->id],
                    [
                        'present_days' => $present,
                        'ot_hours' => $otHours,
                        'piece_qty' => $pieceQty,
                        'gross' => $gross,
                        'deductions' => 0,
                        'net' => $gross,
                        'branch_id' => $run->branch_id,
                    ],
                );
            }
            $run->update(['status' => 'calculated']);
        });

        return $run->fresh('payslips');
    }

    public function gross(float $basic, float $presentDays, float $otHours, int $pieceQty, float $pieceRate): float
    {
        $daily = $basic / 30;
        $hourly = $daily / 8;
        $earned = $daily * $presentDays;
        $overtime = $hourly * $otHours * 2;
        $piece = $pieceQty * $pieceRate;

        return round($earned + $overtime + $piece, 2);
    }
}
