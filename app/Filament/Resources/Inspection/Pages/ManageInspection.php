<?php

namespace App\Filament\Resources\Inspection\Pages;

use App\Filament\Resources\InspectionResource;
use Filament\Resources\Pages\ManageRecords;

class ManageInspection extends ManageRecords
{
    protected static string $resource = InspectionResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
