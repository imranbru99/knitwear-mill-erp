<?php

namespace App\Policies;

use App\Models\SavedView;
use App\Models\User;

class SavedViewPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('saved_views.view') || $user->can('saved_views.manage');
    }

    public function view(User $user, SavedView $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('saved_views.manage');
    }

    public function update(User $user, SavedView $record): bool
    {
        return $user->can('saved_views.manage');
    }

    public function delete(User $user, SavedView $record): bool
    {
        return $user->can('saved_views.manage');
    }
}
