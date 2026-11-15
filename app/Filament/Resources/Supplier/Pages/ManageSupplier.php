<?php

namespace App\Filament\Resources\Supplier\Pages;

use App\Filament\Resources\SupplierResource;
use Filament\Resources\Pages\ManageRecords;

class ManageSupplier extends ManageRecords
{
    protected static string $resource = SupplierResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
