<?php

namespace App\Filament\Resources\DowntimeLog\Pages;

use App\Filament\Resources\DowntimeLogResource;
use Filament\Resources\Pages\ManageRecords;

class ManageDowntimeLog extends ManageRecords
{
    protected static string $resource = DowntimeLogResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
