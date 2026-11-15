<?php

namespace App\Filament\Resources\PurchaseOrder\Pages;

use App\Filament\Resources\PurchaseOrderResource;
use Filament\Resources\Pages\ManageRecords;

class ManagePurchaseOrder extends ManageRecords
{
    protected static string $resource = PurchaseOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
