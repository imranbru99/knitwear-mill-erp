<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Module extends AppendOnlyRecord
{
    protected $table = 'modules';

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'enabled' => 'boolean',
        ]);
    }
}
