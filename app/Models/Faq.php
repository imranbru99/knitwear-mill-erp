<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Faq extends FactoryRecord
{
    protected $table = 'faqs';

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}
