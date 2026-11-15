<?php

namespace App\Filament\Resources\FabricRoll\Pages;

use App\Filament\Resources\FabricRollResource;
use Filament\Resources\Pages\ManageRecords;

class ManageFabricRoll extends ManageRecords
{
    protected static string $resource = FabricRollResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
