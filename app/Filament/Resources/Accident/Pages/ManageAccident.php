<?php

namespace App\Filament\Resources\Accident\Pages;

use App\Filament\Resources\AccidentResource;
use Filament\Resources\Pages\ManageRecords;

class ManageAccident extends ManageRecords
{
    protected static string $resource = AccidentResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
