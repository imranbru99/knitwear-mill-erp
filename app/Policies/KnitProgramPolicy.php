<?php

namespace App\Policies;

use App\Models\KnitProgram;
use App\Models\User;

class KnitProgramPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('knit_programs.view') || $user->can('knit_programs.manage');
    }

    public function view(User $user, KnitProgram $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('knit_programs.manage');
    }

    public function update(User $user, KnitProgram $record): bool
    {
        return $user->can('knit_programs.manage');
    }

    public function delete(User $user, KnitProgram $record): bool
    {
        return $user->can('knit_programs.manage');
    }
}
