<?php

namespace App\Policies;

use App\Models\PurchaseOrder;
use App\Models\User;

class PurchaseOrderPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('purchase_orders.view') || $user->can('purchase_orders.manage');
    }

    public function view(User $user, PurchaseOrder $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('purchase_orders.manage');
    }

    public function update(User $user, PurchaseOrder $record): bool
    {
        return $user->can('purchase_orders.manage');
    }

    public function delete(User $user, PurchaseOrder $record): bool
    {
        return $user->can('purchase_orders.manage');
    }
}
