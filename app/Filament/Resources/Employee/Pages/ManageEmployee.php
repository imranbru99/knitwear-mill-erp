<?php

namespace App\Filament\Resources\Employee\Pages;

use App\Filament\Resources\EmployeeResource;
use Filament\Resources\Pages\ManageRecords;

class ManageEmployee extends ManageRecords
{
    protected static string $resource = EmployeeResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
