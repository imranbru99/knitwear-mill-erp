<?php

namespace App\Filament\Resources\Buyer\Pages;

use App\Filament\Resources\BuyerResource;
use Filament\Resources\Pages\ManageRecords;

class ManageBuyer extends ManageRecords
{
    protected static string $resource = BuyerResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
