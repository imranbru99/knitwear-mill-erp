<?php

namespace App\Filament\Resources\MachineSchedule\Pages;

use App\Filament\Resources\MachineScheduleResource;
use Filament\Resources\Pages\ManageRecords;

class ManageMachineSchedule extends ManageRecords
{
    protected static string $resource = MachineScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
