<?php

namespace App\Policies;

use App\Models\ApprovalRequest;
use App\Models\User;

class ApprovalRequestPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('approval_requests.view') || $user->can('approval_requests.manage');
    }

    public function view(User $user, ApprovalRequest $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('approval_requests.manage');
    }

    public function update(User $user, ApprovalRequest $record): bool
    {
        return $user->can('approval_requests.manage');
    }

    public function delete(User $user, ApprovalRequest $record): bool
    {
        return $user->can('approval_requests.manage');
    }
}
