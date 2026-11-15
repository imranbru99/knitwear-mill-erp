<?php

namespace App\Filament\Resources\Setting\Pages;

use App\Filament\Resources\SettingResource;
use Filament\Resources\Pages\ManageRecords;

class ManageSetting extends ManageRecords
{
    protected static string $resource = SettingResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
