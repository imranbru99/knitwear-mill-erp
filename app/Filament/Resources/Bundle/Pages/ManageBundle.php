<?php

namespace App\Filament\Resources\Bundle\Pages;

use App\Filament\Resources\BundleResource;
use Filament\Resources\Pages\ManageRecords;

class ManageBundle extends ManageRecords
{
    protected static string $resource = BundleResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
