<?php

namespace App\Policies;

use App\Models\Colour;
use App\Models\User;

class ColourPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('colours.view') || $user->can('colours.manage');
    }

    public function view(User $user, Colour $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('colours.manage');
    }

    public function update(User $user, Colour $record): bool
    {
        return $user->can('colours.manage');
    }

    public function delete(User $user, Colour $record): bool
    {
        return $user->can('colours.manage');
    }
}
