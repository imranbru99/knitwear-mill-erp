<?php

namespace App\Filament\Resources\PurchaseOrderLine\Pages;

use App\Filament\Resources\PurchaseOrderLineResource;
use Filament\Resources\Pages\ManageRecords;

class ManagePurchaseOrderLine extends ManageRecords
{
    protected static string $resource = PurchaseOrderLineResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
