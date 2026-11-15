<?php

namespace App\Policies;

use App\Models\BuyerVisit;
use App\Models\User;

class BuyerVisitPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('buyer_visits.view') || $user->can('buyer_visits.manage');
    }

    public function view(User $user, BuyerVisit $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('buyer_visits.manage');
    }

    public function update(User $user, BuyerVisit $record): bool
    {
        return $user->can('buyer_visits.manage');
    }

    public function delete(User $user, BuyerVisit $record): bool
    {
        return $user->can('buyer_visits.manage');
    }
}
