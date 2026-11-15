<?php

namespace App\Policies;

use App\Models\VoucherLine;
use App\Models\User;

class VoucherLinePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('voucher_lines.view') || $user->can('voucher_lines.manage');
    }

    public function view(User $user, VoucherLine $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('voucher_lines.manage');
    }

    public function update(User $user, VoucherLine $record): bool
    {
        return $user->can('voucher_lines.manage');
    }

    public function delete(User $user, VoucherLine $record): bool
    {
        return $user->can('voucher_lines.manage');
    }
}
