<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RequisitionLine extends FactoryRecord
{
    protected $table = 'requisition_lines';

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'qty' => 'decimal:3',
        ]);
    }

    public function requisition(): BelongsTo
    {
        return $this->belongsTo(Requisition::class, 'requisition_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}
