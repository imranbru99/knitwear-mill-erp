<?php

namespace App\Filament\Resources\ExportLc\Pages;

use App\Filament\Resources\ExportLcResource;
use Filament\Resources\Pages\ManageRecords;

class ManageExportLc extends ManageRecords
{
    protected static string $resource = ExportLcResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
