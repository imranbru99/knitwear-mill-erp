<?php

namespace App\Policies;

use App\Models\MaterialRequirement;
use App\Models\User;

class MaterialRequirementPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('material_requirements.view') || $user->can('material_requirements.manage');
    }

    public function view(User $user, MaterialRequirement $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('material_requirements.manage');
    }

    public function update(User $user, MaterialRequirement $record): bool
    {
        return $user->can('material_requirements.manage');
    }

    public function delete(User $user, MaterialRequirement $record): bool
    {
        return $user->can('material_requirements.manage');
    }
}
