<?php

namespace App\Policies;

use App\Models\PurchaseOrderLine;
use App\Models\User;

class PurchaseOrderLinePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('purchase_order_lines.view') || $user->can('purchase_order_lines.manage');
    }

    public function view(User $user, PurchaseOrderLine $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('purchase_order_lines.manage');
    }

    public function update(User $user, PurchaseOrderLine $record): bool
    {
        return $user->can('purchase_order_lines.manage');
    }

    public function delete(User $user, PurchaseOrderLine $record): bool
    {
        return $user->can('purchase_order_lines.manage');
    }
}
