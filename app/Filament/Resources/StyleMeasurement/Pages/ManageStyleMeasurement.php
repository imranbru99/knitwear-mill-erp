<?php

namespace App\Filament\Resources\StyleMeasurement\Pages;

use App\Filament\Resources\StyleMeasurementResource;
use Filament\Resources\Pages\ManageRecords;

class ManageStyleMeasurement extends ManageRecords
{
    protected static string $resource = StyleMeasurementResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
