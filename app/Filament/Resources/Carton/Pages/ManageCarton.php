<?php

namespace App\Filament\Resources\Carton\Pages;

use App\Filament\Resources\CartonResource;
use Filament\Resources\Pages\ManageRecords;

class ManageCarton extends ManageRecords
{
    protected static string $resource = CartonResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
