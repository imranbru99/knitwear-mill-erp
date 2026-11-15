<?php

namespace App\Policies;

use App\Models\JobOpening;
use App\Models\User;

class JobOpeningPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('job_openings.view') || $user->can('job_openings.manage');
    }

    public function view(User $user, JobOpening $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('job_openings.manage');
    }

    public function update(User $user, JobOpening $record): bool
    {
        return $user->can('job_openings.manage');
    }

    public function delete(User $user, JobOpening $record): bool
    {
        return $user->can('job_openings.manage');
    }
}
