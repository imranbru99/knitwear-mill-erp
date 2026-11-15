<?php

namespace App\Policies;

use App\Models\CostingLine;
use App\Models\User;

class CostingLinePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('costing_lines.view') || $user->can('costing_lines.manage');
    }

    public function view(User $user, CostingLine $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('costing_lines.manage');
    }

    public function update(User $user, CostingLine $record): bool
    {
        return $user->can('costing_lines.manage');
    }

    public function delete(User $user, CostingLine $record): bool
    {
        return $user->can('costing_lines.manage');
    }
}
