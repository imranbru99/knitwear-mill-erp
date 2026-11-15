<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Defect extends FactoryRecord
{
    protected $table = 'defects';

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function inspectionDefects(): HasMany
    {
        return $this->hasMany(InspectionDefect::class, 'defect_id');
    }
}
