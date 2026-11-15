<?php

namespace App\Filament\Resources\Module\Pages;

use App\Filament\Resources\ModuleResource;
use Filament\Resources\Pages\ManageRecords;

class ManageModule extends ManageRecords
{
    protected static string $resource = ModuleResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
