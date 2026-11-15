<?php

namespace App\Policies;

use App\Models\YarnCount;
use App\Models\User;

class YarnCountPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('yarn_counts.view') || $user->can('yarn_counts.manage');
    }

    public function view(User $user, YarnCount $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('yarn_counts.manage');
    }

    public function update(User $user, YarnCount $record): bool
    {
        return $user->can('yarn_counts.manage');
    }

    public function delete(User $user, YarnCount $record): bool
    {
        return $user->can('yarn_counts.manage');
    }
}
