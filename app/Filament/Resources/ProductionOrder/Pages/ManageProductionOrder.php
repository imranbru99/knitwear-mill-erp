<?php

namespace App\Filament\Resources\ProductionOrder\Pages;

use App\Filament\Resources\ProductionOrderResource;
use Filament\Resources\Pages\ManageRecords;

class ManageProductionOrder extends ManageRecords
{
    protected static string $resource = ProductionOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
