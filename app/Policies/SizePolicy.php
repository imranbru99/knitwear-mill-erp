<?php

namespace App\Policies;

use App\Models\Size;
use App\Models\User;

class SizePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('sizes.view') || $user->can('sizes.manage');
    }

    public function view(User $user, Size $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('sizes.manage');
    }

    public function update(User $user, Size $record): bool
    {
        return $user->can('sizes.manage');
    }

    public function delete(User $user, Size $record): bool
    {
        return $user->can('sizes.manage');
    }
}
