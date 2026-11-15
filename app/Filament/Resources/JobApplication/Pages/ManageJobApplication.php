<?php

namespace App\Filament\Resources\JobApplication\Pages;

use App\Filament\Resources\JobApplicationResource;
use Filament\Resources\Pages\ManageRecords;

class ManageJobApplication extends ManageRecords
{
    protected static string $resource = JobApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
