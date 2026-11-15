<?php

namespace App\Policies;

use App\Models\ShiftRoster;
use App\Models\User;

class ShiftRosterPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('shift_rosters.view') || $user->can('shift_rosters.manage');
    }

    public function view(User $user, ShiftRoster $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('shift_rosters.manage');
    }

    public function update(User $user, ShiftRoster $record): bool
    {
        return $user->can('shift_rosters.manage');
    }

    public function delete(User $user, ShiftRoster $record): bool
    {
        return $user->can('shift_rosters.manage');
    }
}
