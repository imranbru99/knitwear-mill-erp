<?php

namespace App\Filament\Resources\UnitOfMeasure\Pages;

use App\Filament\Resources\UnitOfMeasureResource;
use Filament\Resources\Pages\ManageRecords;

class ManageUnitOfMeasure extends ManageRecords
{
    protected static string $resource = UnitOfMeasureResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
