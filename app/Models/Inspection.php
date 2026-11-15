<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Inspection extends FactoryRecord
{
    protected $table = 'inspections';

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'aql_major' => 'decimal:3',
            'aql_minor' => 'decimal:3',
        ]);
    }

    public function productionOrder(): BelongsTo
    {
        return $this->belongsTo(ProductionOrder::class, 'production_order_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function inspectionDefects(): HasMany
    {
        return $this->hasMany(InspectionDefect::class, 'inspection_id');
    }
}
