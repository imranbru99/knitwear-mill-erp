<?php

namespace App\Policies;

use App\Models\GrnLine;
use App\Models\User;

class GrnLinePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('grn_lines.view') || $user->can('grn_lines.manage');
    }

    public function view(User $user, GrnLine $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('grn_lines.manage');
    }

    public function update(User $user, GrnLine $record): bool
    {
        return $user->can('grn_lines.manage');
    }

    public function delete(User $user, GrnLine $record): bool
    {
        return $user->can('grn_lines.manage');
    }
}
