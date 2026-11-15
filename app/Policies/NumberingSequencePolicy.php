<?php

namespace App\Policies;

use App\Models\NumberingSequence;
use App\Models\User;

class NumberingSequencePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('numbering_sequences.view') || $user->can('numbering_sequences.manage');
    }

    public function view(User $user, NumberingSequence $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('numbering_sequences.manage');
    }

    public function update(User $user, NumberingSequence $record): bool
    {
        return $user->can('numbering_sequences.manage');
    }

    public function delete(User $user, NumberingSequence $record): bool
    {
        return $user->can('numbering_sequences.manage');
    }
}
