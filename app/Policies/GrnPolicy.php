<?php

namespace App\Policies;

use App\Models\Grn;
use App\Models\User;

class GrnPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('grns.view') || $user->can('grns.manage');
    }

    public function view(User $user, Grn $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('grns.manage');
    }

    public function update(User $user, Grn $record): bool
    {
        return $user->can('grns.manage');
    }

    public function delete(User $user, Grn $record): bool
    {
        return $user->can('grns.manage');
    }
}
