<?php

namespace App\Policies;

use App\Models\IssueType;
use App\Models\User;

class IssueTypePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('issue_types.view') || $user->can('issue_types.manage');
    }

    public function view(User $user, IssueType $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('issue_types.manage');
    }

    public function update(User $user, IssueType $record): bool
    {
        return $user->can('issue_types.manage');
    }

    public function delete(User $user, IssueType $record): bool
    {
        return $user->can('issue_types.manage');
    }
}
