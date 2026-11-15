<?php

namespace App\Policies;

use App\Models\StyleMeasurement;
use App\Models\User;

class StyleMeasurementPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('style_measurements.view') || $user->can('style_measurements.manage');
    }

    public function view(User $user, StyleMeasurement $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('style_measurements.manage');
    }

    public function update(User $user, StyleMeasurement $record): bool
    {
        return $user->can('style_measurements.manage');
    }

    public function delete(User $user, StyleMeasurement $record): bool
    {
        return $user->can('style_measurements.manage');
    }
}
