<?php

namespace App\Filament\Resources\KnitProgram\Pages;

use App\Filament\Resources\KnitProgramResource;
use Filament\Resources\Pages\ManageRecords;

class ManageKnitProgram extends ManageRecords
{
    protected static string $resource = KnitProgramResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
