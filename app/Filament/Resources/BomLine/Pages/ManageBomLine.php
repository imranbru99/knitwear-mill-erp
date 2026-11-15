<?php

namespace App\Filament\Resources\BomLine\Pages;

use App\Filament\Resources\BomLineResource;
use Filament\Resources\Pages\ManageRecords;

class ManageBomLine extends ManageRecords
{
    protected static string $resource = BomLineResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
