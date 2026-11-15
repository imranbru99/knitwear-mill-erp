<?php

namespace App\Filament\Resources\TnaTemplateTask\Pages;

use App\Filament\Resources\TnaTemplateTaskResource;
use Filament\Resources\Pages\ManageRecords;

class ManageTnaTemplateTask extends ManageRecords
{
    protected static string $resource = TnaTemplateTaskResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
