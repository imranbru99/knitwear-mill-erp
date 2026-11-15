<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AuditFinding extends FactoryRecord
{
    protected $table = 'audit_findings';

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'due_on' => 'date',
        ]);
    }

    public function complianceAudit(): BelongsTo
    {
        return $this->belongsTo(ComplianceAudit::class, 'compliance_audit_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}
