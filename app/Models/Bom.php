<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bom extends FactoryRecord
{
    protected $table = 'boms';

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'approved_at' => 'datetime',
        ]);
    }

    public function style(): BelongsTo
    {
        return $this->belongsTo(Style::class, 'style_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function bomLines(): HasMany
    {
        return $this->hasMany(BomLine::class, 'bom_id');
    }
}
