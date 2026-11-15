<?php

namespace App\Policies;

use App\Models\Payslip;
use App\Models\User;

class PayslipPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('payslips.view') || $user->can('payslips.manage');
    }

    public function view(User $user, Payslip $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('payslips.manage');
    }

    public function update(User $user, Payslip $record): bool
    {
        return $user->can('payslips.manage');
    }

    public function delete(User $user, Payslip $record): bool
    {
        return $user->can('payslips.manage');
    }
}
