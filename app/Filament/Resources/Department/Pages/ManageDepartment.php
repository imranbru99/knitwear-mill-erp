<?php

namespace App\Filament\Resources\Department\Pages;

use App\Filament\Resources\DepartmentResource;
use Filament\Resources\Pages\ManageRecords;

class ManageDepartment extends ManageRecords
{
    protected static string $resource = DepartmentResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
