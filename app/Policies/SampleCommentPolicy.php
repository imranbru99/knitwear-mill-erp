<?php

namespace App\Policies;

use App\Models\SampleComment;
use App\Models\User;

class SampleCommentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('sample_comments.view') || $user->can('sample_comments.manage');
    }

    public function view(User $user, SampleComment $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('sample_comments.manage');
    }

    public function update(User $user, SampleComment $record): bool
    {
        return $user->can('sample_comments.manage');
    }

    public function delete(User $user, SampleComment $record): bool
    {
        return $user->can('sample_comments.manage');
    }
}
