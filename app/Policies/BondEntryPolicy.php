<?php

namespace App\Policies;

use App\Models\BondEntry;
use App\Models\User;

class BondEntryPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('bond_entries.view') || $user->can('bond_entries.manage');
    }

    public function view(User $user, BondEntry $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('bond_entries.manage');
    }

    public function update(User $user, BondEntry $record): bool
    {
        return $user->can('bond_entries.manage');
    }

    public function delete(User $user, BondEntry $record): bool
    {
        return $user->can('bond_entries.manage');
    }
}
