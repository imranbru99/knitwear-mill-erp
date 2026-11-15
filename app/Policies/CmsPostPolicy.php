<?php

namespace App\Policies;

use App\Models\CmsPost;
use App\Models\User;

class CmsPostPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('cms_posts.view') || $user->can('cms_posts.manage');
    }

    public function view(User $user, CmsPost $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('cms_posts.manage');
    }

    public function update(User $user, CmsPost $record): bool
    {
        return $user->can('cms_posts.manage');
    }

    public function delete(User $user, CmsPost $record): bool
    {
        return $user->can('cms_posts.manage');
    }
}
