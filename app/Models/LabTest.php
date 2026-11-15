<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LabTest extends FactoryRecord
{
    protected $table = 'lab_tests';

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'passed' => 'boolean',
            'expires_on' => 'date',
        ]);
    }

    public function style(): BelongsTo
    {
        return $this->belongsTo(Style::class, 'style_id');
    }

    public function washLot(): BelongsTo
    {
        return $this->belongsTo(WashLot::class, 'wash_lot_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}
