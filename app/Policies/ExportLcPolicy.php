<?php

namespace App\Policies;

use App\Models\ExportLc;
use App\Models\User;

class ExportLcPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('export_lcs.view') || $user->can('export_lcs.manage');
    }

    public function view(User $user, ExportLc $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('export_lcs.manage');
    }

    public function update(User $user, ExportLc $record): bool
    {
        return $user->can('export_lcs.manage');
    }

    public function delete(User $user, ExportLc $record): bool
    {
        return $user->can('export_lcs.manage');
    }
}
