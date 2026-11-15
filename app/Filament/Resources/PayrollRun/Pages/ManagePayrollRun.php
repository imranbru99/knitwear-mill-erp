<?php

namespace App\Filament\Resources\PayrollRun\Pages;

use App\Filament\Resources\PayrollRunResource;
use Filament\Resources\Pages\ManageRecords;

class ManagePayrollRun extends ManageRecords
{
    protected static string $resource = PayrollRunResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
