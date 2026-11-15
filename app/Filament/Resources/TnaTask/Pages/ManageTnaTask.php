<?php

namespace App\Filament\Resources\TnaTask\Pages;

use App\Filament\Resources\TnaTaskResource;
use Filament\Resources\Pages\ManageRecords;

class ManageTnaTask extends ManageRecords
{
    protected static string $resource = TnaTaskResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
