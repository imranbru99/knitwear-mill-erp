<?php

namespace App\Filament\Resources\Shipment\Pages;

use App\Filament\Resources\ShipmentResource;
use Filament\Resources\Pages\ManageRecords;

class ManageShipment extends ManageRecords
{
    protected static string $resource = ShipmentResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
