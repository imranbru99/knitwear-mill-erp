<?php

namespace App\Filament\Resources\BuyerOrder\Pages;

use App\Filament\Resources\BuyerOrderResource;
use Filament\Resources\Pages\ManageRecords;

class ManageBuyerOrder extends ManageRecords
{
    protected static string $resource = BuyerOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
