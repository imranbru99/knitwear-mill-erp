<?php

namespace App\Filament\Resources\Style\Pages;

use App\Filament\Resources\StyleResource;
use Filament\Resources\Pages\ManageRecords;

class ManageStyle extends ManageRecords
{
    protected static string $resource = StyleResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
