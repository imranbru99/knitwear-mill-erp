<?php

namespace App\Filament\Resources\SampleOrder\Pages;

use App\Filament\Resources\SampleOrderResource;
use Filament\Resources\Pages\ManageRecords;

class ManageSampleOrder extends ManageRecords
{
    protected static string $resource = SampleOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
