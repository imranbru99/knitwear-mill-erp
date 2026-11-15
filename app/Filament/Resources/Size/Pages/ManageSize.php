<?php

namespace App\Filament\Resources\Size\Pages;

use App\Filament\Resources\SizeResource;
use Filament\Resources\Pages\ManageRecords;

class ManageSize extends ManageRecords
{
    protected static string $resource = SizeResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
