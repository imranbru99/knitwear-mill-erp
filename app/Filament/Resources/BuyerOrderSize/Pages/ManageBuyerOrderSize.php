<?php

namespace App\Filament\Resources\BuyerOrderSize\Pages;

use App\Filament\Resources\BuyerOrderSizeResource;
use Filament\Resources\Pages\ManageRecords;

class ManageBuyerOrderSize extends ManageRecords
{
    protected static string $resource = BuyerOrderSizeResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
