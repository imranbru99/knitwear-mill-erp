<?php

namespace App\Policies;

use App\Models\StyleColourway;
use App\Models\User;

class StyleColourwayPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('style_colourways.view') || $user->can('style_colourways.manage');
    }

    public function view(User $user, StyleColourway $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('style_colourways.manage');
    }

    public function update(User $user, StyleColourway $record): bool
    {
        return $user->can('style_colourways.manage');
    }

    public function delete(User $user, StyleColourway $record): bool
    {
        return $user->can('style_colourways.manage');
    }
}
