<?php

namespace App\Policies;

use App\Models\Supplier;
use App\Models\User;

class SupplierPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('suppliers.view') || $user->can('suppliers.manage');
    }

    public function view(User $user, Supplier $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('suppliers.manage');
    }

    public function update(User $user, Supplier $record): bool
    {
        return $user->can('suppliers.manage');
    }

    public function delete(User $user, Supplier $record): bool
    {
        return $user->can('suppliers.manage');
    }
}
