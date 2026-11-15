<?php

namespace App\Policies;

use App\Models\IssueSolution;
use App\Models\User;

class IssueSolutionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('issue_solutions.view') || $user->can('issue_solutions.manage');
    }

    public function view(User $user, IssueSolution $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('issue_solutions.manage');
    }

    public function update(User $user, IssueSolution $record): bool
    {
        return $user->can('issue_solutions.manage');
    }

    public function delete(User $user, IssueSolution $record): bool
    {
        return $user->can('issue_solutions.manage');
    }
}
