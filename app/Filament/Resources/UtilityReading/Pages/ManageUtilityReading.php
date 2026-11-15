<?php

namespace App\Filament\Resources\UtilityReading\Pages;

use App\Filament\Resources\UtilityReadingResource;
use Filament\Resources\Pages\ManageRecords;

class ManageUtilityReading extends ManageRecords
{
    protected static string $resource = UtilityReadingResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
