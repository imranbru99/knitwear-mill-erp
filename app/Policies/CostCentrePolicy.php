<?php

namespace App\Policies;

use App\Models\CostCentre;
use App\Models\User;

class CostCentrePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('cost_centres.view') || $user->can('cost_centres.manage');
    }

    public function view(User $user, CostCentre $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('cost_centres.manage');
    }

    public function update(User $user, CostCentre $record): bool
    {
        return $user->can('cost_centres.manage');
    }

    public function delete(User $user, CostCentre $record): bool
    {
        return $user->can('cost_centres.manage');
    }
}
