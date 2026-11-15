<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OperationBreakdown extends FactoryRecord
{
    protected $table = 'operation_breakdowns';

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'smv' => 'decimal:3',
        ]);
    }

    public function style(): BelongsTo
    {
        return $this->belongsTo(Style::class, 'style_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}
