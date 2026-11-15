<?php

namespace App\Policies;

use App\Models\CmsPage;
use App\Models\User;

class CmsPagePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('cms_pages.view') || $user->can('cms_pages.manage');
    }

    public function view(User $user, CmsPage $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('cms_pages.manage');
    }

    public function update(User $user, CmsPage $record): bool
    {
        return $user->can('cms_pages.manage');
    }

    public function delete(User $user, CmsPage $record): bool
    {
        return $user->can('cms_pages.manage');
    }
}
