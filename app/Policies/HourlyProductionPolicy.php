<?php

namespace App\Policies;

use App\Models\HourlyProduction;
use App\Models\User;

class HourlyProductionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('hourly_productions.view') || $user->can('hourly_productions.manage');
    }

    public function view(User $user, HourlyProduction $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('hourly_productions.manage');
    }

    public function update(User $user, HourlyProduction $record): bool
    {
        return $user->can('hourly_productions.manage');
    }

    public function delete(User $user, HourlyProduction $record): bool
    {
        return $user->can('hourly_productions.manage');
    }
}
