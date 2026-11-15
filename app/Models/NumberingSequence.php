<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NumberingSequence extends AppendOnlyRecord
{
    protected $table = 'numbering_sequences';

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}
