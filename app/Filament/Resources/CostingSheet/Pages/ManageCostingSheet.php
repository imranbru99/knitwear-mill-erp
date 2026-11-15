<?php

namespace App\Filament\Resources\CostingSheet\Pages;

use App\Filament\Resources\CostingSheetResource;
use Filament\Resources\Pages\ManageRecords;

class ManageCostingSheet extends ManageRecords
{
    protected static string $resource = CostingSheetResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
