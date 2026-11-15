<?php

namespace App\Policies;

use App\Models\AuditFinding;
use App\Models\User;

class AuditFindingPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('audit_findings.view') || $user->can('audit_findings.manage');
    }

    public function view(User $user, AuditFinding $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('audit_findings.manage');
    }

    public function update(User $user, AuditFinding $record): bool
    {
        return $user->can('audit_findings.manage');
    }

    public function delete(User $user, AuditFinding $record): bool
    {
        return $user->can('audit_findings.manage');
    }
}
