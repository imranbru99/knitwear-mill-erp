<?php

namespace App\Policies;

use App\Models\FabricRoll;
use App\Models\User;

class FabricRollPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('fabric_rolls.view') || $user->can('fabric_rolls.manage');
    }

    public function view(User $user, FabricRoll $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('fabric_rolls.manage');
    }

    public function update(User $user, FabricRoll $record): bool
    {
        return $user->can('fabric_rolls.manage');
    }

    public function delete(User $user, FabricRoll $record): bool
    {
        return $user->can('fabric_rolls.manage');
    }
}
