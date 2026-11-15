<?php

namespace App\Policies;

use App\Models\ApprovalFlow;
use App\Models\User;

class ApprovalFlowPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('approval_flows.view') || $user->can('approval_flows.manage');
    }

    public function view(User $user, ApprovalFlow $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('approval_flows.manage');
    }

    public function update(User $user, ApprovalFlow $record): bool
    {
        return $user->can('approval_flows.manage');
    }

    public function delete(User $user, ApprovalFlow $record): bool
    {
        return $user->can('approval_flows.manage');
    }
}
