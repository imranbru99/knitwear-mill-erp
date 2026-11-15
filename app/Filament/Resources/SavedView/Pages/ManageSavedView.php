<?php

namespace App\Filament\Resources\SavedView\Pages;

use App\Filament\Resources\SavedViewResource;
use Filament\Resources\Pages\ManageRecords;

class ManageSavedView extends ManageRecords
{
    protected static string $resource = SavedViewResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
