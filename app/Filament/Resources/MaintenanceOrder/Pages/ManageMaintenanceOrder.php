<?php

namespace App\Filament\Resources\MaintenanceOrder\Pages;

use App\Filament\Resources\MaintenanceOrderResource;
use Filament\Resources\Pages\ManageRecords;

class ManageMaintenanceOrder extends ManageRecords
{
    protected static string $resource = MaintenanceOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
