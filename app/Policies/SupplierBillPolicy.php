<?php

namespace App\Policies;

use App\Models\SupplierBill;
use App\Models\User;

class SupplierBillPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('supplier_bills.view') || $user->can('supplier_bills.manage');
    }

    public function view(User $user, SupplierBill $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('supplier_bills.manage');
    }

    public function update(User $user, SupplierBill $record): bool
    {
        return $user->can('supplier_bills.manage');
    }

    public function delete(User $user, SupplierBill $record): bool
    {
        return $user->can('supplier_bills.manage');
    }
}
