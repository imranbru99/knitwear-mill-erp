<?php

namespace App\Policies;

use App\Models\Setting;
use App\Models\User;

class SettingPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('settings.view') || $user->can('settings.manage');
    }

    public function view(User $user, Setting $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('settings.manage');
    }

    public function update(User $user, Setting $record): bool
    {
        return $user->can('settings.manage');
    }

    public function delete(User $user, Setting $record): bool
    {
        return $user->can('settings.manage');
    }
}
