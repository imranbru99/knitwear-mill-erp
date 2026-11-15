<?php

namespace App\Policies;

use App\Models\PostingRule;
use App\Models\User;

class PostingRulePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('posting_rules.view') || $user->can('posting_rules.manage');
    }

    public function view(User $user, PostingRule $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('posting_rules.manage');
    }

    public function update(User $user, PostingRule $record): bool
    {
        return $user->can('posting_rules.manage');
    }

    public function delete(User $user, PostingRule $record): bool
    {
        return $user->can('posting_rules.manage');
    }
}
