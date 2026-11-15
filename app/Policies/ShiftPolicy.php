<?php

namespace App\Policies;

use App\Models\Shift;
use App\Models\User;

class ShiftPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('shifts.view') || $user->can('shifts.manage');
    }

    public function view(User $user, Shift $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('shifts.manage');
    }

    public function update(User $user, Shift $record): bool
    {
        return $user->can('shifts.manage');
    }

    public function delete(User $user, Shift $record): bool
    {
        return $user->can('shifts.manage');
    }
}
