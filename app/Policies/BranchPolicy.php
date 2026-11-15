<?php

namespace App\Policies;

use App\Models\Branch;
use App\Models\User;

class BranchPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('branches.view') || $user->can('branches.manage');
    }

    public function view(User $user, Branch $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('branches.manage');
    }

    public function update(User $user, Branch $record): bool
    {
        return $user->can('branches.manage');
    }

    public function delete(User $user, Branch $record): bool
    {
        return $user->can('branches.manage');
    }
}
