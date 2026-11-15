<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IssueEscalation extends AppendOnlyRecord
{
    protected $table = 'issue_escalations';

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'escalated_at' => 'datetime',
        ]);
    }

    public function issue(): BelongsTo
    {
        return $this->belongsTo(Issue::class, 'issue_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}
