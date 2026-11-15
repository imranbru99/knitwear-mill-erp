<?php

namespace App\Policies;

use App\Models\Asset;
use App\Models\User;

class AssetPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('assets.view') || $user->can('assets.manage');
    }

    public function view(User $user, Asset $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('assets.manage');
    }

    public function update(User $user, Asset $record): bool
    {
        return $user->can('assets.manage');
    }

    public function delete(User $user, Asset $record): bool
    {
        return $user->can('assets.manage');
    }
}
