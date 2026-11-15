<?php

namespace App\Policies;

use App\Models\JobApplication;
use App\Models\User;

class JobApplicationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('job_applications.view') || $user->can('job_applications.manage');
    }

    public function view(User $user, JobApplication $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('job_applications.manage');
    }

    public function update(User $user, JobApplication $record): bool
    {
        return $user->can('job_applications.manage');
    }

    public function delete(User $user, JobApplication $record): bool
    {
        return $user->can('job_applications.manage');
    }
}
