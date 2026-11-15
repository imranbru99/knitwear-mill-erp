<?php

namespace App\Policies;

use App\Models\YarnInspection;
use App\Models\User;

class YarnInspectionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('yarn_inspections.view') || $user->can('yarn_inspections.manage');
    }

    public function view(User $user, YarnInspection $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('yarn_inspections.manage');
    }

    public function update(User $user, YarnInspection $record): bool
    {
        return $user->can('yarn_inspections.manage');
    }

    public function delete(User $user, YarnInspection $record): bool
    {
        return $user->can('yarn_inspections.manage');
    }
}
