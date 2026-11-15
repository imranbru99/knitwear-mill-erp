<?php

namespace App\Policies;

use App\Models\Bundle;
use App\Models\User;

class BundlePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('bundles.view') || $user->can('bundles.manage');
    }

    public function view(User $user, Bundle $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('bundles.manage');
    }

    public function update(User $user, Bundle $record): bool
    {
        return $user->can('bundles.manage');
    }

    public function delete(User $user, Bundle $record): bool
    {
        return $user->can('bundles.manage');
    }
}
