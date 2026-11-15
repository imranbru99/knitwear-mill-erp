<?php

namespace App\Filament\Resources\VoucherLine\Pages;

use App\Filament\Resources\VoucherLineResource;
use Filament\Resources\Pages\ManageRecords;

class ManageVoucherLine extends ManageRecords
{
    protected static string $resource = VoucherLineResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
