<?php

namespace App\Policies;

use App\Models\ApprovalAction;
use App\Models\User;

class ApprovalActionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('approval_actions.view') || $user->can('approval_actions.manage');
    }

    public function view(User $user, ApprovalAction $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('approval_actions.manage');
    }

    public function update(User $user, ApprovalAction $record): bool
    {
        return $user->can('approval_actions.manage');
    }

    public function delete(User $user, ApprovalAction $record): bool
    {
        return $user->can('approval_actions.manage');
    }
}
