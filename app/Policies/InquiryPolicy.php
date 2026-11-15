<?php

namespace App\Policies;

use App\Models\Inquiry;
use App\Models\User;

class InquiryPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('inquiries.view') || $user->can('inquiries.manage');
    }

    public function view(User $user, Inquiry $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('inquiries.manage');
    }

    public function update(User $user, Inquiry $record): bool
    {
        return $user->can('inquiries.manage');
    }

    public function delete(User $user, Inquiry $record): bool
    {
        return $user->can('inquiries.manage');
    }
}
