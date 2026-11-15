<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TechPackVersion extends FactoryRecord
{
    protected $table = 'tech_pack_versions';

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'extracted' => 'array',
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
}
