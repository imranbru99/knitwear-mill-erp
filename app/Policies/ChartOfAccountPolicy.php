<?php

namespace App\Policies;

use App\Models\ChartOfAccount;
use App\Models\User;

class ChartOfAccountPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('chart_of_accounts.view') || $user->can('chart_of_accounts.manage');
    }

    public function view(User $user, ChartOfAccount $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('chart_of_accounts.manage');
    }

    public function update(User $user, ChartOfAccount $record): bool
    {
        return $user->can('chart_of_accounts.manage');
    }

    public function delete(User $user, ChartOfAccount $record): bool
    {
        return $user->can('chart_of_accounts.manage');
    }
}
