<?php

namespace App\Policies;

use App\Models\SampleOrder;
use App\Models\User;

class SampleOrderPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('sample_orders.view') || $user->can('sample_orders.manage');
    }

    public function view(User $user, SampleOrder $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('sample_orders.manage');
    }

    public function update(User $user, SampleOrder $record): bool
    {
        return $user->can('sample_orders.manage');
    }

    public function delete(User $user, SampleOrder $record): bool
    {
        return $user->can('sample_orders.manage');
    }
}
