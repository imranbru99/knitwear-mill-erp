<?php

namespace App\Policies;

use App\Models\WashLot;
use App\Models\User;

class WashLotPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('wash_lots.view') || $user->can('wash_lots.manage');
    }

    public function view(User $user, WashLot $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('wash_lots.manage');
    }

    public function update(User $user, WashLot $record): bool
    {
        return $user->can('wash_lots.manage');
    }

    public function delete(User $user, WashLot $record): bool
    {
        return $user->can('wash_lots.manage');
    }
}
