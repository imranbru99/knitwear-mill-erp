<?php

namespace App\Filament\Resources\Licence\Pages;

use App\Filament\Resources\LicenceResource;
use Filament\Resources\Pages\ManageRecords;

class ManageLicence extends ManageRecords
{
    protected static string $resource = LicenceResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
