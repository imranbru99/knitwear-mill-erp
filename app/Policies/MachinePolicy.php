<?php

namespace App\Policies;

use App\Models\Machine;
use App\Models\User;

class MachinePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('machines.view') || $user->can('machines.manage');
    }

    public function view(User $user, Machine $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('machines.manage');
    }

    public function update(User $user, Machine $record): bool
    {
        return $user->can('machines.manage');
    }

    public function delete(User $user, Machine $record): bool
    {
        return $user->can('machines.manage');
    }
}
