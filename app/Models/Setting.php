<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Setting extends AppendOnlyRecord
{
    protected $table = 'settings';

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'value' => 'array',
        ]);
    }
}
