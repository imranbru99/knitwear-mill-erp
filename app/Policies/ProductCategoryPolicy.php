<?php

namespace App\Policies;

use App\Models\ProductCategory;
use App\Models\User;

class ProductCategoryPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('product_categories.view') || $user->can('product_categories.manage');
    }

    public function view(User $user, ProductCategory $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('product_categories.manage');
    }

    public function update(User $user, ProductCategory $record): bool
    {
        return $user->can('product_categories.manage');
    }

    public function delete(User $user, ProductCategory $record): bool
    {
        return $user->can('product_categories.manage');
    }
}
