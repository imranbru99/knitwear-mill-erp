<?php

namespace App\Filament\Resources\CuttingOrder\Pages;

use App\Filament\Resources\CuttingOrderResource;
use Filament\Resources\Pages\ManageRecords;

class ManageCuttingOrder extends ManageRecords
{
    protected static string $resource = CuttingOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
