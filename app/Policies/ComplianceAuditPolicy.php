<?php

namespace App\Policies;

use App\Models\ComplianceAudit;
use App\Models\User;

class ComplianceAuditPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('compliance_audits.view') || $user->can('compliance_audits.manage');
    }

    public function view(User $user, ComplianceAudit $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('compliance_audits.manage');
    }

    public function update(User $user, ComplianceAudit $record): bool
    {
        return $user->can('compliance_audits.manage');
    }

    public function delete(User $user, ComplianceAudit $record): bool
    {
        return $user->can('compliance_audits.manage');
    }
}
