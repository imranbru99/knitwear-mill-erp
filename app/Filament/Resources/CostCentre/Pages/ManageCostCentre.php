<?php

namespace App\Filament\Resources\CostCentre\Pages;

use App\Filament\Resources\CostCentreResource;
use Filament\Resources\Pages\ManageRecords;

class ManageCostCentre extends ManageRecords
{
    protected static string $resource = CostCentreResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
