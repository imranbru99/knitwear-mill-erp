<?php

namespace App\Filament\Resources\StyleColourway\Pages;

use App\Filament\Resources\StyleColourwayResource;
use Filament\Resources\Pages\ManageRecords;

class ManageStyleColourway extends ManageRecords
{
    protected static string $resource = StyleColourwayResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
