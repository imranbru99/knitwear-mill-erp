<?php

namespace App\Policies;

use App\Models\SewingLine;
use App\Models\User;

class SewingLinePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('sewing_lines.view') || $user->can('sewing_lines.manage');
    }

    public function view(User $user, SewingLine $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('sewing_lines.manage');
    }

    public function update(User $user, SewingLine $record): bool
    {
        return $user->can('sewing_lines.manage');
    }

    public function delete(User $user, SewingLine $record): bool
    {
        return $user->can('sewing_lines.manage');
    }
}
