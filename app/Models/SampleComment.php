<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SampleComment extends FactoryRecord
{
    protected $table = 'sample_comments';

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'due_on' => 'date',
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
