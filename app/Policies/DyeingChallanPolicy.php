<?php

namespace App\Policies;

use App\Models\DyeingChallan;
use App\Models\User;

class DyeingChallanPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('dyeing_challans.view') || $user->can('dyeing_challans.manage');
    }

    public function view(User $user, DyeingChallan $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('dyeing_challans.manage');
    }

    public function update(User $user, DyeingChallan $record): bool
    {
        return $user->can('dyeing_challans.manage');
    }

    public function delete(User $user, DyeingChallan $record): bool
    {
        return $user->can('dyeing_challans.manage');
    }
}
