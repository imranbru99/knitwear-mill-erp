<?php

namespace App\Policies;

use App\Models\AuditLog;
use App\Models\User;

class AuditLogPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('audit_logs.view') || $user->can('audit_logs.manage');
    }

    public function view(User $user, AuditLog $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('audit_logs.manage');
    }

    public function update(User $user, AuditLog $record): bool
    {
        return $user->can('audit_logs.manage');
    }

    public function delete(User $user, AuditLog $record): bool
    {
        return $user->can('audit_logs.manage');
    }
}
