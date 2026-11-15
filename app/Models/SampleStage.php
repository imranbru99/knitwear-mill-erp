<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SampleStage extends FactoryRecord
{
    protected $table = 'sample_stages';

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'started_at' => 'datetime',
            'finished_at' => 'datetime',
        ]);
    }

    public function sampleOrder(): BelongsTo
    {
        return $this->belongsTo(SampleOrder::class, 'sample_order_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}
