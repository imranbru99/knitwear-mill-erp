<?php

namespace App\Policies;

use App\Models\RfqQuote;
use App\Models\User;

class RfqQuotePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('rfq_quotes.view') || $user->can('rfq_quotes.manage');
    }

    public function view(User $user, RfqQuote $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('rfq_quotes.manage');
    }

    public function update(User $user, RfqQuote $record): bool
    {
        return $user->can('rfq_quotes.manage');
    }

    public function delete(User $user, RfqQuote $record): bool
    {
        return $user->can('rfq_quotes.manage');
    }
}
