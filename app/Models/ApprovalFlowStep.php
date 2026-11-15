<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ApprovalFlowStep extends FactoryRecord
{
    protected $table = 'approval_flow_steps';

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'min_amount' => 'decimal:3',
        ]);
    }

    public function approvalFlow(): BelongsTo
    {
        return $this->belongsTo(ApprovalFlow::class, 'approval_flow_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}
