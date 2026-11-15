<?php

namespace App\Policies;

use App\Models\TnaTemplate;
use App\Models\User;

class TnaTemplatePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('tna_templates.view') || $user->can('tna_templates.manage');
    }

    public function view(User $user, TnaTemplate $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('tna_templates.manage');
    }

    public function update(User $user, TnaTemplate $record): bool
    {
        return $user->can('tna_templates.manage');
    }

    public function delete(User $user, TnaTemplate $record): bool
    {
        return $user->can('tna_templates.manage');
    }
}
