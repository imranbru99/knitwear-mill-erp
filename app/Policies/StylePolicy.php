<?php

namespace App\Policies;

use App\Models\Style;
use App\Models\User;

class StylePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('styles.view') || $user->can('styles.manage');
    }

    public function view(User $user, Style $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('styles.manage');
    }

    public function update(User $user, Style $record): bool
    {
        return $user->can('styles.manage');
    }

    public function delete(User $user, Style $record): bool
    {
        return $user->can('styles.manage');
    }
}
