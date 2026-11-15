<?php

namespace App\Policies;

use App\Models\LeaveRequest;
use App\Models\User;

class LeaveRequestPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('leave_requests.view') || $user->can('leave_requests.manage');
    }

    public function view(User $user, LeaveRequest $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('leave_requests.manage');
    }

    public function update(User $user, LeaveRequest $record): bool
    {
        return $user->can('leave_requests.manage');
    }

    public function delete(User $user, LeaveRequest $record): bool
    {
        return $user->can('leave_requests.manage');
    }
}
