<?php

namespace App\Policies;

use App\Models\ApprovalFlowStep;
use App\Models\User;

class ApprovalFlowStepPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('approval_flow_steps.view') || $user->can('approval_flow_steps.manage');
    }

    public function view(User $user, ApprovalFlowStep $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('approval_flow_steps.manage');
    }

    public function update(User $user, ApprovalFlowStep $record): bool
    {
        return $user->can('approval_flow_steps.manage');
    }

    public function delete(User $user, ApprovalFlowStep $record): bool
    {
        return $user->can('approval_flow_steps.manage');
    }
}
