<?php

namespace App\Policies;

use App\Models\Inspection;
use App\Models\User;

class InspectionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('inspections.view') || $user->can('inspections.manage');
    }

    public function view(User $user, Inspection $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('inspections.manage');
    }

    public function update(User $user, Inspection $record): bool
    {
        return $user->can('inspections.manage');
    }

    public function delete(User $user, Inspection $record): bool
    {
        return $user->can('inspections.manage');
    }
}
