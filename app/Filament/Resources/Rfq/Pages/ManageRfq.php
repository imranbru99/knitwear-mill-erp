<?php

namespace App\Filament\Resources\Rfq\Pages;

use App\Filament\Resources\RfqResource;
use Filament\Resources\Pages\ManageRecords;

class ManageRfq extends ManageRecords
{
    protected static string $resource = RfqResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
