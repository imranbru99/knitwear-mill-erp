<?php

namespace App\Policies;

use App\Models\PackingList;
use App\Models\User;

class PackingListPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('packing_lists.view') || $user->can('packing_lists.manage');
    }

    public function view(User $user, PackingList $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('packing_lists.manage');
    }

    public function update(User $user, PackingList $record): bool
    {
        return $user->can('packing_lists.manage');
    }

    public function delete(User $user, PackingList $record): bool
    {
        return $user->can('packing_lists.manage');
    }
}
