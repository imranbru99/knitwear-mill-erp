<?php

namespace App\Filament\Resources\GatePass\Pages;

use App\Filament\Resources\GatePassResource;
use Filament\Resources\Pages\ManageRecords;

class ManageGatePass extends ManageRecords
{
    protected static string $resource = GatePassResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
