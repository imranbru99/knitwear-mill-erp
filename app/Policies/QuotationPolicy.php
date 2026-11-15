<?php

namespace App\Policies;

use App\Models\Quotation;
use App\Models\User;

class QuotationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('quotations.view') || $user->can('quotations.manage');
    }

    public function view(User $user, Quotation $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('quotations.manage');
    }

    public function update(User $user, Quotation $record): bool
    {
        return $user->can('quotations.manage');
    }

    public function delete(User $user, Quotation $record): bool
    {
        return $user->can('quotations.manage');
    }
}
