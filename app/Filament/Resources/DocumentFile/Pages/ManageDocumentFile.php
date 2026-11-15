<?php

namespace App\Filament\Resources\DocumentFile\Pages;

use App\Filament\Resources\DocumentFileResource;
use Filament\Resources\Pages\ManageRecords;

class ManageDocumentFile extends ManageRecords
{
    protected static string $resource = DocumentFileResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
