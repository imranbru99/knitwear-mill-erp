<?php

namespace App\Policies;

use App\Models\Employee;
use App\Models\User;

class EmployeePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('employees.view') || $user->can('employees.manage');
    }

    public function view(User $user, Employee $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('employees.manage');
    }

    public function update(User $user, Employee $record): bool
    {
        return $user->can('employees.manage');
    }

    public function delete(User $user, Employee $record): bool
    {
        return $user->can('employees.manage');
    }
}
