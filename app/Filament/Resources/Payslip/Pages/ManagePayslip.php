<?php

namespace App\Filament\Resources\Payslip\Pages;

use App\Filament\Resources\PayslipResource;
use Filament\Resources\Pages\ManageRecords;

class ManagePayslip extends ManageRecords
{
    protected static string $resource = PayslipResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
