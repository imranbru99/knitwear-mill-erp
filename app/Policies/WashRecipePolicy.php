<?php

namespace App\Policies;

use App\Models\WashRecipe;
use App\Models\User;

class WashRecipePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('wash_recipes.view') || $user->can('wash_recipes.manage');
    }

    public function view(User $user, WashRecipe $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('wash_recipes.manage');
    }

    public function update(User $user, WashRecipe $record): bool
    {
        return $user->can('wash_recipes.manage');
    }

    public function delete(User $user, WashRecipe $record): bool
    {
        return $user->can('wash_recipes.manage');
    }
}
