<?php

namespace App\Policies;

use App\Models\Testimonial;
use App\Models\User;

class TestimonialPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('testimonials.view') || $user->can('testimonials.manage');
    }

    public function view(User $user, Testimonial $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('testimonials.manage');
    }

    public function update(User $user, Testimonial $record): bool
    {
        return $user->can('testimonials.manage');
    }

    public function delete(User $user, Testimonial $record): bool
    {
        return $user->can('testimonials.manage');
    }
}
