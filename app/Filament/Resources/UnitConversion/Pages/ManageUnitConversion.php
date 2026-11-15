<?php

namespace App\Filament\Resources\UnitConversion\Pages;

use App\Filament\Resources\UnitConversionResource;
use Filament\Resources\Pages\ManageRecords;

class ManageUnitConversion extends ManageRecords
{
    protected static string $resource = UnitConversionResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
