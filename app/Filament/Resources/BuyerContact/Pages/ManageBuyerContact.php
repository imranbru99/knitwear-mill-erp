<?php

namespace App\Filament\Resources\BuyerContact\Pages;

use App\Filament\Resources\BuyerContactResource;
use Filament\Resources\Pages\ManageRecords;

class ManageBuyerContact extends ManageRecords
{
    protected static string $resource = BuyerContactResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
