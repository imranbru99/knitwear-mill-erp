<?php

namespace App\Filament\Resources\InspectionDefect\Pages;

use App\Filament\Resources\InspectionDefectResource;
use Filament\Resources\Pages\ManageRecords;

class ManageInspectionDefect extends ManageRecords
{
    protected static string $resource = InspectionDefectResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
