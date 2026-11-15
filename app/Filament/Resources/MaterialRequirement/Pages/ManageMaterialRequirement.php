<?php

namespace App\Filament\Resources\MaterialRequirement\Pages;

use App\Filament\Resources\MaterialRequirementResource;
use Filament\Resources\Pages\ManageRecords;

class ManageMaterialRequirement extends ManageRecords
{
    protected static string $resource = MaterialRequirementResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
