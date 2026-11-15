<?php

namespace App\Policies;

use App\Models\Module;
use App\Models\User;

class ModulePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('modules.view') || $user->can('modules.manage');
    }

    public function view(User $user, Module $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('modules.manage');
    }

    public function update(User $user, Module $record): bool
    {
        return $user->can('modules.manage');
    }

    public function delete(User $user, Module $record): bool
    {
        return $user->can('modules.manage');
    }
}
