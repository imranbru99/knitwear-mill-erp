<?php

namespace App\Filament\Resources\YarnInspection\Pages;

use App\Filament\Resources\YarnInspectionResource;
use Filament\Resources\Pages\ManageRecords;

class ManageYarnInspection extends ManageRecords
{
    protected static string $resource = YarnInspectionResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
