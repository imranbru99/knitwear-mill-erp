<?php

namespace App\Filament\Resources\ExchangeRate\Pages;

use App\Filament\Resources\ExchangeRateResource;
use Filament\Resources\Pages\ManageRecords;

class ManageExchangeRate extends ManageRecords
{
    protected static string $resource = ExchangeRateResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
