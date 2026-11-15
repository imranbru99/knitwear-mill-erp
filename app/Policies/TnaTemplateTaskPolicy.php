<?php

namespace App\Policies;

use App\Models\TnaTemplateTask;
use App\Models\User;

class TnaTemplateTaskPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('tna_template_tasks.view') || $user->can('tna_template_tasks.manage');
    }

    public function view(User $user, TnaTemplateTask $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('tna_template_tasks.manage');
    }

    public function update(User $user, TnaTemplateTask $record): bool
    {
        return $user->can('tna_template_tasks.manage');
    }

    public function delete(User $user, TnaTemplateTask $record): bool
    {
        return $user->can('tna_template_tasks.manage');
    }
}
