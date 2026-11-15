<?php

namespace App\Filament\Resources\SupplierBill\Pages;

use App\Filament\Resources\SupplierBillResource;
use Filament\Resources\Pages\ManageRecords;

class ManageSupplierBill extends ManageRecords
{
    protected static string $resource = SupplierBillResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
