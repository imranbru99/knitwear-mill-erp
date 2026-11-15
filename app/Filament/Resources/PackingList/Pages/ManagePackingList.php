<?php

namespace App\Filament\Resources\PackingList\Pages;

use App\Filament\Resources\PackingListResource;
use Filament\Resources\Pages\ManageRecords;

class ManagePackingList extends ManageRecords
{
    protected static string $resource = PackingListResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
