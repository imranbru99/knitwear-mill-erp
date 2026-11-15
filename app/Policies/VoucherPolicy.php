<?php

namespace App\Policies;

use App\Models\Voucher;
use App\Models\User;

class VoucherPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('vouchers.view') || $user->can('vouchers.manage');
    }

    public function view(User $user, Voucher $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('vouchers.manage');
    }

    public function update(User $user, Voucher $record): bool
    {
        return $user->can('vouchers.manage');
    }

    public function delete(User $user, Voucher $record): bool
    {
        return $user->can('vouchers.manage');
    }
}
