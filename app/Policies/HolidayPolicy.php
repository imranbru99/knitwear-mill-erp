<?php

namespace App\Policies;

use App\Models\Holiday;
use App\Models\User;

class HolidayPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('holidays.view') || $user->can('holidays.manage');
    }

    public function view(User $user, Holiday $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('holidays.manage');
    }

    public function update(User $user, Holiday $record): bool
    {
        return $user->can('holidays.manage');
    }

    public function delete(User $user, Holiday $record): bool
    {
        return $user->can('holidays.manage');
    }
}
