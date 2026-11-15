<?php

namespace App\Filament\Resources\GrnLine\Pages;

use App\Filament\Resources\GrnLineResource;
use Filament\Resources\Pages\ManageRecords;

class ManageGrnLine extends ManageRecords
{
    protected static string $resource = GrnLineResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
