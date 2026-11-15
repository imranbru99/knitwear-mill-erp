<?php

namespace App\Policies;

use App\Models\Carton;
use App\Models\User;

class CartonPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('cartons.view') || $user->can('cartons.manage');
    }

    public function view(User $user, Carton $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('cartons.manage');
    }

    public function update(User $user, Carton $record): bool
    {
        return $user->can('cartons.manage');
    }

    public function delete(User $user, Carton $record): bool
    {
        return $user->can('cartons.manage');
    }
}
