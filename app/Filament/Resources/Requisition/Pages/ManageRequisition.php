<?php

namespace App\Filament\Resources\Requisition\Pages;

use App\Filament\Resources\RequisitionResource;
use Filament\Resources\Pages\ManageRecords;

class ManageRequisition extends ManageRecords
{
    protected static string $resource = RequisitionResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
