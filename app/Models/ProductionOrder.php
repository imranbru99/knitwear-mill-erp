<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductionOrder extends FactoryRecord
{
    protected $table = 'production_orders';

    public function buyerOrder(): BelongsTo
    {
        return $this->belongsTo(BuyerOrder::class, 'buyer_order_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function issues(): HasMany
    {
        return $this->hasMany(Issue::class, 'production_order_id');
    }

    public function tnaTasks(): HasMany
    {
        return $this->hasMany(TnaTask::class, 'production_order_id');
    }

    public function materialRequirements(): HasMany
    {
        return $this->hasMany(MaterialRequirement::class, 'production_order_id');
    }

    public function machineSchedules(): HasMany
    {
        return $this->hasMany(MachineSchedule::class, 'production_order_id');
    }

    public function bundles(): HasMany
    {
        return $this->hasMany(Bundle::class, 'production_order_id');
    }

    public function fabricRolls(): HasMany
    {
        return $this->hasMany(FabricRoll::class, 'production_order_id');
    }

    public function washLots(): HasMany
    {
        return $this->hasMany(WashLot::class, 'production_order_id');
    }

    public function cuttingOrders(): HasMany
    {
        return $this->hasMany(CuttingOrder::class, 'production_order_id');
    }

    public function hourlyProductions(): HasMany
    {
        return $this->hasMany(HourlyProduction::class, 'production_order_id');
    }

    public function inspections(): HasMany
    {
        return $this->hasMany(Inspection::class, 'production_order_id');
    }

    public function packingLists(): HasMany
    {
        return $this->hasMany(PackingList::class, 'production_order_id');
    }

    public function voucherLines(): HasMany
    {
        return $this->hasMany(VoucherLine::class, 'production_order_id');
    }
}
