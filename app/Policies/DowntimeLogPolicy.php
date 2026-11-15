<?php

namespace App\Policies;

use App\Models\DowntimeLog;
use App\Models\User;

class DowntimeLogPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('downtime_logs.view') || $user->can('downtime_logs.manage');
    }

    public function view(User $user, DowntimeLog $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('downtime_logs.manage');
    }

    public function update(User $user, DowntimeLog $record): bool
    {
        return $user->can('downtime_logs.manage');
    }

    public function delete(User $user, DowntimeLog $record): bool
    {
        return $user->can('downtime_logs.manage');
    }
}
