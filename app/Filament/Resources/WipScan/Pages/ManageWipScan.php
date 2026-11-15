<?php

namespace App\Filament\Resources\WipScan\Pages;

use App\Filament\Resources\WipScanResource;
use Filament\Resources\Pages\ManageRecords;

class ManageWipScan extends ManageRecords
{
    protected static string $resource = WipScanResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
