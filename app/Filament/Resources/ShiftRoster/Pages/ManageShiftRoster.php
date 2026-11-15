<?php

namespace App\Filament\Resources\ShiftRoster\Pages;

use App\Filament\Resources\ShiftRosterResource;
use Filament\Resources\Pages\ManageRecords;

class ManageShiftRoster extends ManageRecords
{
    protected static string $resource = ShiftRosterResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
