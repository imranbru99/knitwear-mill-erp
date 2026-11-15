<?php

namespace App\Policies;

use App\Models\BuyerOrderSize;
use App\Models\User;

class BuyerOrderSizePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('buyer_order_sizes.view') || $user->can('buyer_order_sizes.manage');
    }

    public function view(User $user, BuyerOrderSize $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('buyer_order_sizes.manage');
    }

    public function update(User $user, BuyerOrderSize $record): bool
    {
        return $user->can('buyer_order_sizes.manage');
    }

    public function delete(User $user, BuyerOrderSize $record): bool
    {
        return $user->can('buyer_order_sizes.manage');
    }
}
