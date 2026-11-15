<?php

namespace App\Filament\Resources\Defect\Pages;

use App\Filament\Resources\DefectResource;
use Filament\Resources\Pages\ManageRecords;

class ManageDefect extends ManageRecords
{
    protected static string $resource = DefectResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
