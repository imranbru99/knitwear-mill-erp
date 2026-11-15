<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class YarnInspection extends FactoryRecord
{
    protected $table = 'yarn_inspections';

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'count_ok' => 'boolean',
            'strength_ok' => 'boolean',
            'hairiness_ok' => 'boolean',
            'neps_ok' => 'boolean',
            'shade_ok' => 'boolean',
            'cone_weight_ok' => 'boolean',
            'wax_ok' => 'boolean',
        ]);
    }

    public function grnLine(): BelongsTo
    {
        return $this->belongsTo(GrnLine::class, 'grn_line_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}
