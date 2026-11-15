<?php

namespace App\Policies;

use App\Models\UtilityReading;
use App\Models\User;

class UtilityReadingPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('utility_readings.view') || $user->can('utility_readings.manage');
    }

    public function view(User $user, UtilityReading $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('utility_readings.manage');
    }

    public function update(User $user, UtilityReading $record): bool
    {
        return $user->can('utility_readings.manage');
    }

    public function delete(User $user, UtilityReading $record): bool
    {
        return $user->can('utility_readings.manage');
    }
}
