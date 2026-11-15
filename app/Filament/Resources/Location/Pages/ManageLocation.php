<?php

namespace App\Filament\Resources\Location\Pages;

use App\Filament\Resources\LocationResource;
use Filament\Resources\Pages\ManageRecords;

class ManageLocation extends ManageRecords
{
    protected static string $resource = LocationResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
