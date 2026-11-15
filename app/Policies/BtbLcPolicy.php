<?php

namespace App\Policies;

use App\Models\BtbLc;
use App\Models\User;

class BtbLcPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('btb_lcs.view') || $user->can('btb_lcs.manage');
    }

    public function view(User $user, BtbLc $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('btb_lcs.manage');
    }

    public function update(User $user, BtbLc $record): bool
    {
        return $user->can('btb_lcs.manage');
    }

    public function delete(User $user, BtbLc $record): bool
    {
        return $user->can('btb_lcs.manage');
    }
}
