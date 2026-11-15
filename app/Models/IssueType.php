<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IssueType extends FactoryRecord
{
    protected $table = 'issue_types';

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'is_active' => 'boolean',
        ]);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function issues(): HasMany
    {
        return $this->hasMany(Issue::class, 'issue_type_id');
    }

    public function issueSolutions(): HasMany
    {
        return $this->hasMany(IssueSolution::class, 'issue_type_id');
    }
}
