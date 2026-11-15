<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExportLc extends FactoryRecord
{
    protected $table = 'export_lcs';

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'amount' => 'decimal:3',
            'expiry_on' => 'date',
            'shipment_deadline' => 'date',
            'presentation_deadline' => 'date',
        ]);
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class, 'buyer_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function btbLcs(): HasMany
    {
        return $this->hasMany(BtbLc::class, 'export_lc_id');
    }

    public function bondEntries(): HasMany
    {
        return $this->hasMany(BondEntry::class, 'export_lc_id');
    }
}
