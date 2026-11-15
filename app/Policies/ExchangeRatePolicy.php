<?php

namespace App\Policies;

use App\Models\ExchangeRate;
use App\Models\User;

class ExchangeRatePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('exchange_rates.view') || $user->can('exchange_rates.manage');
    }

    public function view(User $user, ExchangeRate $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('exchange_rates.manage');
    }

    public function update(User $user, ExchangeRate $record): bool
    {
        return $user->can('exchange_rates.manage');
    }

    public function delete(User $user, ExchangeRate $record): bool
    {
        return $user->can('exchange_rates.manage');
    }
}
