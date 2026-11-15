<?php

namespace App\Filament\Resources\StockBalance\Pages;

use App\Filament\Resources\StockBalanceResource;
use Filament\Resources\Pages\ManageRecords;

class ManageStockBalance extends ManageRecords
{
    protected static string $resource = StockBalanceResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
