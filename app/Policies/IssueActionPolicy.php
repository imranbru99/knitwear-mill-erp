<?php

namespace App\Policies;

use App\Models\IssueAction;
use App\Models\User;

class IssueActionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('issue_actions.view') || $user->can('issue_actions.manage');
    }

    public function view(User $user, IssueAction $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('issue_actions.manage');
    }

    public function update(User $user, IssueAction $record): bool
    {
        return $user->can('issue_actions.manage');
    }

    public function delete(User $user, IssueAction $record): bool
    {
        return $user->can('issue_actions.manage');
    }
}
