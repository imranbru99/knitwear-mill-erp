<?php

namespace App\Filament\Resources\Holiday\Pages;

use App\Filament\Resources\HolidayResource;
use Filament\Resources\Pages\ManageRecords;

class ManageHoliday extends ManageRecords
{
    protected static string $resource = HolidayResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
