<?php

namespace App\Policies;

use App\Models\CostingSheet;
use App\Models\User;

class CostingSheetPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('costing_sheets.view') || $user->can('costing_sheets.manage');
    }

    public function view(User $user, CostingSheet $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('costing_sheets.manage');
    }

    public function update(User $user, CostingSheet $record): bool
    {
        return $user->can('costing_sheets.manage');
    }

    public function delete(User $user, CostingSheet $record): bool
    {
        return $user->can('costing_sheets.manage');
    }
}
