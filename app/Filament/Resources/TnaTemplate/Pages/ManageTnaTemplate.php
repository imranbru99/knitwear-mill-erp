<?php

namespace App\Filament\Resources\TnaTemplate\Pages;

use App\Filament\Resources\TnaTemplateResource;
use Filament\Resources\Pages\ManageRecords;

class ManageTnaTemplate extends ManageRecords
{
    protected static string $resource = TnaTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
