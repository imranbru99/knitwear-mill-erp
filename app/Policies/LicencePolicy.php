<?php

namespace App\Policies;

use App\Models\Licence;
use App\Models\User;

class LicencePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('licences.view') || $user->can('licences.manage');
    }

    public function view(User $user, Licence $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('licences.manage');
    }

    public function update(User $user, Licence $record): bool
    {
        return $user->can('licences.manage');
    }

    public function delete(User $user, Licence $record): bool
    {
        return $user->can('licences.manage');
    }
}
