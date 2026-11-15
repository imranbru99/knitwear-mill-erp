<?php

namespace App\Filament\Resources\SewingLine\Pages;

use App\Filament\Resources\SewingLineResource;
use Filament\Resources\Pages\ManageRecords;

class ManageSewingLine extends ManageRecords
{
    protected static string $resource = SewingLineResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
