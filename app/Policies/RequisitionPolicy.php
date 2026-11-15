<?php

namespace App\Policies;

use App\Models\Requisition;
use App\Models\User;

class RequisitionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('requisitions.view') || $user->can('requisitions.manage');
    }

    public function view(User $user, Requisition $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('requisitions.manage');
    }

    public function update(User $user, Requisition $record): bool
    {
        return $user->can('requisitions.manage');
    }

    public function delete(User $user, Requisition $record): bool
    {
        return $user->can('requisitions.manage');
    }
}
