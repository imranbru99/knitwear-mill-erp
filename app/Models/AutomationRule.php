<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AutomationRule extends FactoryRecord
{
    protected $table = 'automation_rules';

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'enabled' => 'boolean',
            'thresholds' => 'array',
        ]);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}
