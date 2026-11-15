<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BtbLc extends FactoryRecord
{
    protected $table = 'btb_lcs';

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'amount' => 'decimal:3',
            'limit_amount' => 'decimal:3',
            'maturity_on' => 'date',
        ]);
    }

    public function exportLc(): BelongsTo
    {
        return $this->belongsTo(ExportLc::class, 'export_lc_id');
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
