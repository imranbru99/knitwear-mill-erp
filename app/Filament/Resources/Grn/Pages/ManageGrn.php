<?php

namespace App\Filament\Resources\Grn\Pages;

use App\Filament\Resources\GrnResource;
use Filament\Resources\Pages\ManageRecords;

class ManageGrn extends ManageRecords
{
    protected static string $resource = GrnResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
