<?php

namespace App\Policies;

use App\Models\InspectionDefect;
use App\Models\User;

class InspectionDefectPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('inspection_defects.view') || $user->can('inspection_defects.manage');
    }

    public function view(User $user, InspectionDefect $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('inspection_defects.manage');
    }

    public function update(User $user, InspectionDefect $record): bool
    {
        return $user->can('inspection_defects.manage');
    }

    public function delete(User $user, InspectionDefect $record): bool
    {
        return $user->can('inspection_defects.manage');
    }
}
