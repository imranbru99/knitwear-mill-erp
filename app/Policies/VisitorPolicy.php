<?php

namespace App\Policies;

use App\Models\Visitor;
use App\Models\User;

class VisitorPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('visitors.view') || $user->can('visitors.manage');
    }

    public function view(User $user, Visitor $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('visitors.manage');
    }

    public function update(User $user, Visitor $record): bool
    {
        return $user->can('visitors.manage');
    }

    public function delete(User $user, Visitor $record): bool
    {
        return $user->can('visitors.manage');
    }
}
