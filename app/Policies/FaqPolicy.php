<?php

namespace App\Policies;

use App\Models\Faq;
use App\Models\User;

class FaqPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('faqs.view') || $user->can('faqs.manage');
    }

    public function view(User $user, Faq $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('faqs.manage');
    }

    public function update(User $user, Faq $record): bool
    {
        return $user->can('faqs.manage');
    }

    public function delete(User $user, Faq $record): bool
    {
        return $user->can('faqs.manage');
    }
}
