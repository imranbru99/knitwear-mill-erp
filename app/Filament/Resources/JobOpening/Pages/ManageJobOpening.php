<?php

namespace App\Filament\Resources\JobOpening\Pages;

use App\Filament\Resources\JobOpeningResource;
use Filament\Resources\Pages\ManageRecords;

class ManageJobOpening extends ManageRecords
{
    protected static string $resource = JobOpeningResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
