<?php

namespace App\Policies;

use App\Models\StockLedger;
use App\Models\User;

class StockLedgerPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('stock_ledgers.view') || $user->can('stock_ledgers.manage');
    }

    public function view(User $user, StockLedger $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('stock_ledgers.manage');
    }

    public function update(User $user, StockLedger $record): bool
    {
        return $user->can('stock_ledgers.manage');
    }

    public function delete(User $user, StockLedger $record): bool
    {
        return $user->can('stock_ledgers.manage');
    }
}
