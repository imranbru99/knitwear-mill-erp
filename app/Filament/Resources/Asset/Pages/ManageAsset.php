<?php

namespace App\Filament\Resources\Asset\Pages;

use App\Filament\Resources\AssetResource;
use Filament\Resources\Pages\ManageRecords;

class ManageAsset extends ManageRecords
{
    protected static string $resource = AssetResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
