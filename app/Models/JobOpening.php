<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobOpening extends FactoryRecord
{
    protected $table = 'job_openings';

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function jobApplications(): HasMany
    {
        return $this->hasMany(JobApplication::class, 'job_opening_id');
    }
}
