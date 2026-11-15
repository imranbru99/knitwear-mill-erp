<?php

namespace App\Policies;

use App\Models\ProductionEntry;
use App\Models\User;

class ProductionEntryPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('production_entries.view') || $user->can('production_entries.manage');
    }

    public function view(User $user, ProductionEntry $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('production_entries.manage');
    }

    public function update(User $user, ProductionEntry $record): bool
    {
        return $user->can('production_entries.manage');
    }

    public function delete(User $user, ProductionEntry $record): bool
    {
        return $user->can('production_entries.manage');
    }
}
