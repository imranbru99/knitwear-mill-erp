<?php

namespace App\Policies;

use App\Models\BomLine;
use App\Models\User;

class BomLinePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('bom_lines.view') || $user->can('bom_lines.manage');
    }

    public function view(User $user, BomLine $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('bom_lines.manage');
    }

    public function update(User $user, BomLine $record): bool
    {
        return $user->can('bom_lines.manage');
    }

    public function delete(User $user, BomLine $record): bool
    {
        return $user->can('bom_lines.manage');
    }
}
