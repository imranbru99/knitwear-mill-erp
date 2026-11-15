<?php

namespace App\Policies;

use App\Models\OperationBreakdown;
use App\Models\User;

class OperationBreakdownPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('operation_breakdowns.view') || $user->can('operation_breakdowns.manage');
    }

    public function view(User $user, OperationBreakdown $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('operation_breakdowns.manage');
    }

    public function update(User $user, OperationBreakdown $record): bool
    {
        return $user->can('operation_breakdowns.manage');
    }

    public function delete(User $user, OperationBreakdown $record): bool
    {
        return $user->can('operation_breakdowns.manage');
    }
}
