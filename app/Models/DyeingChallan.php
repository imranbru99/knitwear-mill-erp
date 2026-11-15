<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DyeingChallan extends FactoryRecord
{
    protected $table = 'dyeing_challans';

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'sent_weight' => 'decimal:3',
            'received_weight' => 'decimal:3',
            'loss_percent' => 'decimal:3',
        ]);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}
