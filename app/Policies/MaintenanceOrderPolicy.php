<?php

namespace App\Policies;

use App\Models\MaintenanceOrder;
use App\Models\User;

class MaintenanceOrderPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('maintenance_orders.view') || $user->can('maintenance_orders.manage');
    }

    public function view(User $user, MaintenanceOrder $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('maintenance_orders.manage');
    }

    public function update(User $user, MaintenanceOrder $record): bool
    {
        return $user->can('maintenance_orders.manage');
    }

    public function delete(User $user, MaintenanceOrder $record): bool
    {
        return $user->can('maintenance_orders.manage');
    }
}
