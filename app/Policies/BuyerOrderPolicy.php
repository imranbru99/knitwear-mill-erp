<?php

namespace App\Policies;

use App\Models\BuyerOrder;
use App\Models\User;

class BuyerOrderPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('buyer_orders.view') || $user->can('buyer_orders.manage');
    }

    public function view(User $user, BuyerOrder $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('buyer_orders.manage');
    }

    public function update(User $user, BuyerOrder $record): bool
    {
        return $user->can('buyer_orders.manage');
    }

    public function delete(User $user, BuyerOrder $record): bool
    {
        return $user->can('buyer_orders.manage');
    }
}
