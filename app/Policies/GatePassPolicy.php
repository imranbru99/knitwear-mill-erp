<?php

namespace App\Policies;

use App\Models\GatePass;
use App\Models\User;

class GatePassPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('gate_passes.view') || $user->can('gate_passes.manage');
    }

    public function view(User $user, GatePass $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('gate_passes.manage');
    }

    public function update(User $user, GatePass $record): bool
    {
        return $user->can('gate_passes.manage');
    }

    public function delete(User $user, GatePass $record): bool
    {
        return $user->can('gate_passes.manage');
    }
}
