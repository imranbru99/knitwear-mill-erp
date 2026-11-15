<?php

namespace App\Filament\Resources\Machine\Pages;

use App\Filament\Resources\MachineResource;
use Filament\Resources\Pages\ManageRecords;

class ManageMachine extends ManageRecords
{
    protected static string $resource = MachineResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
