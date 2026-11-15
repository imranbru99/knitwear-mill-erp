<?php

namespace App\Policies;

use App\Models\SampleStage;
use App\Models\User;

class SampleStagePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('sample_stages.view') || $user->can('sample_stages.manage');
    }

    public function view(User $user, SampleStage $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('sample_stages.manage');
    }

    public function update(User $user, SampleStage $record): bool
    {
        return $user->can('sample_stages.manage');
    }

    public function delete(User $user, SampleStage $record): bool
    {
        return $user->can('sample_stages.manage');
    }
}
