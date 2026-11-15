<?php

namespace App\Filament\Resources\RequisitionLine\Pages;

use App\Filament\Resources\RequisitionLineResource;
use Filament\Resources\Pages\ManageRecords;

class ManageRequisitionLine extends ManageRecords
{
    protected static string $resource = RequisitionLineResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
