<?php

namespace App\Filament\Resources\CostingLine\Pages;

use App\Filament\Resources\CostingLineResource;
use Filament\Resources\Pages\ManageRecords;

class ManageCostingLine extends ManageRecords
{
    protected static string $resource = CostingLineResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
