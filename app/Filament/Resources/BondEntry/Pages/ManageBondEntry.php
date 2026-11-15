<?php

namespace App\Filament\Resources\BondEntry\Pages;

use App\Filament\Resources\BondEntryResource;
use Filament\Resources\Pages\ManageRecords;

class ManageBondEntry extends ManageRecords
{
    protected static string $resource = BondEntryResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
