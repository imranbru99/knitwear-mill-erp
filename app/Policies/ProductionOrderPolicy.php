<?php

namespace App\Policies;

use App\Models\ProductionOrder;
use App\Models\User;

class ProductionOrderPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('production_orders.view') || $user->can('production_orders.manage');
    }

    public function view(User $user, ProductionOrder $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('production_orders.manage');
    }

    public function update(User $user, ProductionOrder $record): bool
    {
        return $user->can('production_orders.manage');
    }

    public function delete(User $user, ProductionOrder $record): bool
    {
        return $user->can('production_orders.manage');
    }
}
