<?php

namespace App\Policies;

use App\Models\Department;
use App\Models\User;

class DepartmentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('departments.view') || $user->can('departments.manage');
    }

    public function view(User $user, Department $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('departments.manage');
    }

    public function update(User $user, Department $record): bool
    {
        return $user->can('departments.manage');
    }

    public function delete(User $user, Department $record): bool
    {
        return $user->can('departments.manage');
    }
}
