<?php

namespace App\Policies;

use App\Models\Bom;
use App\Models\User;

class BomPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('boms.view') || $user->can('boms.manage');
    }

    public function view(User $user, Bom $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('boms.manage');
    }

    public function update(User $user, Bom $record): bool
    {
        return $user->can('boms.manage');
    }

    public function delete(User $user, Bom $record): bool
    {
        return $user->can('boms.manage');
    }
}
