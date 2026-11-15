<?php

namespace App\Policies;

use App\Models\CuttingOrder;
use App\Models\User;

class CuttingOrderPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('cutting_orders.view') || $user->can('cutting_orders.manage');
    }

    public function view(User $user, CuttingOrder $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('cutting_orders.manage');
    }

    public function update(User $user, CuttingOrder $record): bool
    {
        return $user->can('cutting_orders.manage');
    }

    public function delete(User $user, CuttingOrder $record): bool
    {
        return $user->can('cutting_orders.manage');
    }
}
