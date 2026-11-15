<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CmsPage extends FactoryRecord
{
    protected $table = 'cms_pages';

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}
