<?php

namespace App\Policies;

use App\Models\IssueEscalation;
use App\Models\User;

class IssueEscalationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('issue_escalations.view') || $user->can('issue_escalations.manage');
    }

    public function view(User $user, IssueEscalation $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('issue_escalations.manage');
    }

    public function update(User $user, IssueEscalation $record): bool
    {
        return $user->can('issue_escalations.manage');
    }

    public function delete(User $user, IssueEscalation $record): bool
    {
        return $user->can('issue_escalations.manage');
    }
}
