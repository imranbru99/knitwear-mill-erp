<?php

namespace App\Policies;

use App\Models\PayrollRun;
use App\Models\User;

class PayrollRunPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('payroll_runs.view') || $user->can('payroll_runs.manage');
    }

    public function view(User $user, PayrollRun $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('payroll_runs.manage');
    }

    public function update(User $user, PayrollRun $record): bool
    {
        return $user->can('payroll_runs.manage');
    }

    public function delete(User $user, PayrollRun $record): bool
    {
        return $user->can('payroll_runs.manage');
    }
}
