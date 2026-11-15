<?php

namespace App\Policies;

use App\Models\AutomationRule;
use App\Models\User;

class AutomationRulePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('automation_rules.view') || $user->can('automation_rules.manage');
    }

    public function view(User $user, AutomationRule $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('automation_rules.manage');
    }

    public function update(User $user, AutomationRule $record): bool
    {
        return $user->can('automation_rules.manage');
    }

    public function delete(User $user, AutomationRule $record): bool
    {
        return $user->can('automation_rules.manage');
    }
}
