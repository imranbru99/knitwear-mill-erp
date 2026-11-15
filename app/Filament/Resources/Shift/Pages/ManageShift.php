<?php

namespace App\Filament\Resources\Shift\Pages;

use App\Filament\Resources\ShiftResource;
use Filament\Resources\Pages\ManageRecords;

class ManageShift extends ManageRecords
{
    protected static string $resource = ShiftResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
