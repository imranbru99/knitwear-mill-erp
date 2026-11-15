<?php

namespace App\Filament\Resources\Voucher\Pages;

use App\Filament\Resources\VoucherResource;
use Filament\Resources\Pages\ManageRecords;

class ManageVoucher extends ManageRecords
{
    protected static string $resource = VoucherResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
