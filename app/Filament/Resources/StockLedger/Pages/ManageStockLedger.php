<?php

namespace App\Filament\Resources\StockLedger\Pages;

use App\Filament\Resources\StockLedgerResource;
use Filament\Resources\Pages\ManageRecords;

class ManageStockLedger extends ManageRecords
{
    protected static string $resource = StockLedgerResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
