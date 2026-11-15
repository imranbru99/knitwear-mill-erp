<?php

namespace App\Policies;

use App\Models\StockBalance;
use App\Models\User;

class StockBalancePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('stock_balances.view') || $user->can('stock_balances.manage');
    }

    public function view(User $user, StockBalance $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('stock_balances.manage');
    }

    public function update(User $user, StockBalance $record): bool
    {
        return $user->can('stock_balances.manage');
    }

    public function delete(User $user, StockBalance $record): bool
    {
        return $user->can('stock_balances.manage');
    }
}
