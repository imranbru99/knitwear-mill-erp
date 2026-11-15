<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Testimonial extends FactoryRecord
{
    protected $table = 'testimonials';

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}
