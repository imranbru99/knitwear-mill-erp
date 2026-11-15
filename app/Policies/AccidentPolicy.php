<?php

namespace App\Policies;

use App\Models\Accident;
use App\Models\User;

class AccidentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('accidents.view') || $user->can('accidents.manage');
    }

    public function view(User $user, Accident $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('accidents.manage');
    }

    public function update(User $user, Accident $record): bool
    {
        return $user->can('accidents.manage');
    }

    public function delete(User $user, Accident $record): bool
    {
        return $user->can('accidents.manage');
    }
}
