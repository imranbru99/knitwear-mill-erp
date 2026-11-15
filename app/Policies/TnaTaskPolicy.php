<?php

namespace App\Policies;

use App\Models\TnaTask;
use App\Models\User;

class TnaTaskPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('tna_tasks.view') || $user->can('tna_tasks.manage');
    }

    public function view(User $user, TnaTask $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('tna_tasks.manage');
    }

    public function update(User $user, TnaTask $record): bool
    {
        return $user->can('tna_tasks.manage');
    }

    public function delete(User $user, TnaTask $record): bool
    {
        return $user->can('tna_tasks.manage');
    }
}
