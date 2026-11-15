<?php

namespace App\Filament\Resources\Quotation\Pages;

use App\Filament\Resources\QuotationResource;
use Filament\Resources\Pages\ManageRecords;

class ManageQuotation extends ManageRecords
{
    protected static string $resource = QuotationResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
