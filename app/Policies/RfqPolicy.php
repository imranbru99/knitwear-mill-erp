<?php

namespace App\Policies;

use App\Models\Rfq;
use App\Models\User;

class RfqPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('rfqs.view') || $user->can('rfqs.manage');
    }

    public function view(User $user, Rfq $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('rfqs.manage');
    }

    public function update(User $user, Rfq $record): bool
    {
        return $user->can('rfqs.manage');
    }

    public function delete(User $user, Rfq $record): bool
    {
        return $user->can('rfqs.manage');
    }
}
