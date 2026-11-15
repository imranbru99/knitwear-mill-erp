<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BondEntry extends FactoryRecord
{
    protected $table = 'bond_entries';

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'yarn_import_kg' => 'decimal:3',
            'yarn_consumed_kg' => 'decimal:3',
            'balance_kg' => 'decimal:3',
        ]);
    }

    public function exportLc(): BelongsTo
    {
        return $this->belongsTo(ExportLc::class, 'export_lc_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}
