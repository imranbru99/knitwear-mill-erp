<?php

namespace App\Filament\Resources\Colour\Pages;

use App\Filament\Resources\ColourResource;
use Filament\Resources\Pages\ManageRecords;

class ManageColour extends ManageRecords
{
    protected static string $resource = ColourResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
