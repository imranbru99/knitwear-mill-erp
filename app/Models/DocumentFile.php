<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DocumentFile extends FactoryRecord
{
    protected $table = 'document_files';

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'expires_on' => 'date',
        ]);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}
