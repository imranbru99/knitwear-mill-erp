<?php

namespace App\Filament\Resources\YarnCount\Pages;

use App\Filament\Resources\YarnCountResource;
use Filament\Resources\Pages\ManageRecords;

class ManageYarnCount extends ManageRecords
{
    protected static string $resource = YarnCountResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
