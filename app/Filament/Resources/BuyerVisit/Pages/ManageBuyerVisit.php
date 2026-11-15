<?php

namespace App\Filament\Resources\BuyerVisit\Pages;

use App\Filament\Resources\BuyerVisitResource;
use Filament\Resources\Pages\ManageRecords;

class ManageBuyerVisit extends ManageRecords
{
    protected static string $resource = BuyerVisitResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
