<?php

namespace App\Filament\Resources\Vehicle\Pages;

use App\Filament\Resources\VehicleResource;
use Filament\Resources\Pages\ManageRecords;

class ManageVehicle extends ManageRecords
{
    protected static string $resource = VehicleResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
