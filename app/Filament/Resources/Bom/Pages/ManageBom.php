<?php

namespace App\Filament\Resources\Bom\Pages;

use App\Filament\Resources\BomResource;
use Filament\Resources\Pages\ManageRecords;

class ManageBom extends ManageRecords
{
    protected static string $resource = BomResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
