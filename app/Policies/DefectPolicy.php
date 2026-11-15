<?php

namespace App\Policies;

use App\Models\Defect;
use App\Models\User;

class DefectPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('defects.view') || $user->can('defects.manage');
    }

    public function view(User $user, Defect $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('defects.manage');
    }

    public function update(User $user, Defect $record): bool
    {
        return $user->can('defects.manage');
    }

    public function delete(User $user, Defect $record): bool
    {
        return $user->can('defects.manage');
    }
}
