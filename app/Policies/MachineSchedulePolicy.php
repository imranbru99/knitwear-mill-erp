<?php

namespace App\Policies;

use App\Models\MachineSchedule;
use App\Models\User;

class MachineSchedulePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('machine_schedules.view') || $user->can('machine_schedules.manage');
    }

    public function view(User $user, MachineSchedule $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('machine_schedules.manage');
    }

    public function update(User $user, MachineSchedule $record): bool
    {
        return $user->can('machine_schedules.manage');
    }

    public function delete(User $user, MachineSchedule $record): bool
    {
        return $user->can('machine_schedules.manage');
    }
}
