<?php

namespace App\Policies;

use App\Models\Buyer;
use App\Models\User;

class BuyerPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('buyers.view') || $user->can('buyers.manage');
    }

    public function view(User $user, Buyer $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('buyers.manage');
    }

    public function update(User $user, Buyer $record): bool
    {
        return $user->can('buyers.manage');
    }

    public function delete(User $user, Buyer $record): bool
    {
        return $user->can('buyers.manage');
    }
}
