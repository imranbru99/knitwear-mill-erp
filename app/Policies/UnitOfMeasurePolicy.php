<?php

namespace App\Policies;

use App\Models\UnitOfMeasure;
use App\Models\User;

class UnitOfMeasurePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('unit_of_measures.view') || $user->can('unit_of_measures.manage');
    }

    public function view(User $user, UnitOfMeasure $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('unit_of_measures.manage');
    }

    public function update(User $user, UnitOfMeasure $record): bool
    {
        return $user->can('unit_of_measures.manage');
    }

    public function delete(User $user, UnitOfMeasure $record): bool
    {
        return $user->can('unit_of_measures.manage');
    }
}
