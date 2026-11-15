<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ComplianceAudit extends FactoryRecord
{
    protected $table = 'compliance_audits';

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'audited_on' => 'date',
            'next_due' => 'date',
        ]);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function auditFindings(): HasMany
    {
        return $this->hasMany(AuditFinding::class, 'compliance_audit_id');
    }
}
