<?php

namespace App\Filament\Resources\TechPackVersion\Pages;

use App\Filament\Resources\TechPackVersionResource;
use Filament\Resources\Pages\ManageRecords;

class ManageTechPackVersion extends ManageRecords
{
    protected static string $resource = TechPackVersionResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
