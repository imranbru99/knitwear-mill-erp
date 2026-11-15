<?php

namespace App\Policies;

use App\Models\Issue;
use App\Models\User;

class IssuePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('issues.view') || $user->can('issues.manage');
    }

    public function view(User $user, Issue $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('issues.manage');
    }

    public function update(User $user, Issue $record): bool
    {
        return $user->can('issues.manage');
    }

    public function delete(User $user, Issue $record): bool
    {
        return $user->can('issues.manage');
    }
}
