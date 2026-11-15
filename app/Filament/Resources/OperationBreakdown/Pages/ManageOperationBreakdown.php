<?php

namespace App\Filament\Resources\OperationBreakdown\Pages;

use App\Filament\Resources\OperationBreakdownResource;
use Filament\Resources\Pages\ManageRecords;

class ManageOperationBreakdown extends ManageRecords
{
    protected static string $resource = OperationBreakdownResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
