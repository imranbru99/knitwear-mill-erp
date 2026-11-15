<?php

namespace App\Policies;

use App\Models\RequisitionLine;
use App\Models\User;

class RequisitionLinePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('requisition_lines.view') || $user->can('requisition_lines.manage');
    }

    public function view(User $user, RequisitionLine $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('requisition_lines.manage');
    }

    public function update(User $user, RequisitionLine $record): bool
    {
        return $user->can('requisition_lines.manage');
    }

    public function delete(User $user, RequisitionLine $record): bool
    {
        return $user->can('requisition_lines.manage');
    }
}
