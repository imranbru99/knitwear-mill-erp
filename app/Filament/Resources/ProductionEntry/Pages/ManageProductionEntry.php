<?php

namespace App\Filament\Resources\ProductionEntry\Pages;

use App\Filament\Resources\ProductionEntryResource;
use Filament\Resources\Pages\ManageRecords;

class ManageProductionEntry extends ManageRecords
{
    protected static string $resource = ProductionEntryResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
