<?php

namespace App\Policies;

use App\Models\Location;
use App\Models\User;

class LocationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('locations.view') || $user->can('locations.manage');
    }

    public function view(User $user, Location $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('locations.manage');
    }

    public function update(User $user, Location $record): bool
    {
        return $user->can('locations.manage');
    }

    public function delete(User $user, Location $record): bool
    {
        return $user->can('locations.manage');
    }
}
