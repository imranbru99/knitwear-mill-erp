<?php

namespace App\Policies;

use App\Models\TechPackVersion;
use App\Models\User;

class TechPackVersionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('tech_pack_versions.view') || $user->can('tech_pack_versions.manage');
    }

    public function view(User $user, TechPackVersion $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('tech_pack_versions.manage');
    }

    public function update(User $user, TechPackVersion $record): bool
    {
        return $user->can('tech_pack_versions.manage');
    }

    public function delete(User $user, TechPackVersion $record): bool
    {
        return $user->can('tech_pack_versions.manage');
    }
}
