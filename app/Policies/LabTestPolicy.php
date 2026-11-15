<?php

namespace App\Policies;

use App\Models\LabTest;
use App\Models\User;

class LabTestPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('lab_tests.view') || $user->can('lab_tests.manage');
    }

    public function view(User $user, LabTest $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('lab_tests.manage');
    }

    public function update(User $user, LabTest $record): bool
    {
        return $user->can('lab_tests.manage');
    }

    public function delete(User $user, LabTest $record): bool
    {
        return $user->can('lab_tests.manage');
    }
}
