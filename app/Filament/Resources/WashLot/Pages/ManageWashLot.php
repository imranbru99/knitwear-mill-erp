<?php

namespace App\Filament\Resources\WashLot\Pages;

use App\Filament\Resources\WashLotResource;
use Filament\Resources\Pages\ManageRecords;

class ManageWashLot extends ManageRecords
{
    protected static string $resource = WashLotResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
