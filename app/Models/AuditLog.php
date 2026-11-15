<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AuditLog extends AppendOnlyRecord
{
    protected $table = 'audit_logs';

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'before' => 'array',
            'after' => 'array',
        ]);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}
