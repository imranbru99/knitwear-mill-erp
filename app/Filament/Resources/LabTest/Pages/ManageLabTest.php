<?php

namespace App\Filament\Resources\LabTest\Pages;

use App\Filament\Resources\LabTestResource;
use Filament\Resources\Pages\ManageRecords;

class ManageLabTest extends ManageRecords
{
    protected static string $resource = LabTestResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
