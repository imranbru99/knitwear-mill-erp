<?php

namespace App\Filament\Resources\HourlyProduction\Pages;

use App\Filament\Resources\HourlyProductionResource;
use Filament\Resources\Pages\ManageRecords;

class ManageHourlyProduction extends ManageRecords
{
    protected static string $resource = HourlyProductionResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
