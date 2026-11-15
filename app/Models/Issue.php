<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Issue extends FactoryRecord
{
    protected $table = 'issues';

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'due_at' => 'datetime',
            'sla_due_at' => 'datetime',
            'verified_at' => 'datetime',
            'cost_impact' => 'decimal:3',
            'chronic' => 'boolean',
        ]);
    }

    public function issueType(): BelongsTo
    {
        return $this->belongsTo(IssueType::class, 'issue_type_id');
    }

    public function productionOrder(): BelongsTo
    {
        return $this->belongsTo(ProductionOrder::class, 'production_order_id');
    }

    public function machine(): BelongsTo
    {
        return $this->belongsTo(Machine::class, 'machine_id');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'owner_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Issue::class, 'parent_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function issues(): HasMany
    {
        return $this->hasMany(Issue::class, 'parent_id');
    }

    public function issueActions(): HasMany
    {
        return $this->hasMany(IssueAction::class, 'issue_id');
    }

    public function issueEscalations(): HasMany
    {
        return $this->hasMany(IssueEscalation::class, 'issue_id');
    }
}
