<?php

namespace App\Policies;

use App\Models\DocumentFile;
use App\Models\User;

class DocumentFilePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('document_files.view') || $user->can('document_files.manage');
    }

    public function view(User $user, DocumentFile $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('document_files.manage');
    }

    public function update(User $user, DocumentFile $record): bool
    {
        return $user->can('document_files.manage');
    }

    public function delete(User $user, DocumentFile $record): bool
    {
        return $user->can('document_files.manage');
    }
}
