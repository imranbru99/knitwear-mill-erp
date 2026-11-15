<?php

namespace App\Policies;

use App\Models\UnitConversion;
use App\Models\User;

class UnitConversionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('unit_conversions.view') || $user->can('unit_conversions.manage');
    }

    public function view(User $user, UnitConversion $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('unit_conversions.manage');
    }

    public function update(User $user, UnitConversion $record): bool
    {
        return $user->can('unit_conversions.manage');
    }

    public function delete(User $user, UnitConversion $record): bool
    {
        return $user->can('unit_conversions.manage');
    }
}
