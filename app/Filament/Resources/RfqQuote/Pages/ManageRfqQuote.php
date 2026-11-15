<?php

namespace App\Filament\Resources\RfqQuote\Pages;

use App\Filament\Resources\RfqQuoteResource;
use Filament\Resources\Pages\ManageRecords;

class ManageRfqQuote extends ManageRecords
{
    protected static string $resource = RfqQuoteResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
