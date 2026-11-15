<?php

namespace App\Policies;

use App\Models\Vehicle;
use App\Models\User;

class VehiclePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('vehicles.view') || $user->can('vehicles.manage');
    }

    public function view(User $user, Vehicle $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('vehicles.manage');
    }

    public function update(User $user, Vehicle $record): bool
    {
        return $user->can('vehicles.manage');
    }

    public function delete(User $user, Vehicle $record): bool
    {
        return $user->can('vehicles.manage');
    }
}
