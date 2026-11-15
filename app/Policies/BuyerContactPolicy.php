<?php

namespace App\Policies;

use App\Models\BuyerContact;
use App\Models\User;

class BuyerContactPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('buyer_contacts.view') || $user->can('buyer_contacts.manage');
    }

    public function view(User $user, BuyerContact $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('buyer_contacts.manage');
    }

    public function update(User $user, BuyerContact $record): bool
    {
        return $user->can('buyer_contacts.manage');
    }

    public function delete(User $user, BuyerContact $record): bool
    {
        return $user->can('buyer_contacts.manage');
    }
}
