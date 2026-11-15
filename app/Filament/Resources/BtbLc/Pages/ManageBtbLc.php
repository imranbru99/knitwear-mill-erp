<?php

namespace App\Filament\Resources\BtbLc\Pages;

use App\Filament\Resources\BtbLcResource;
use Filament\Resources\Pages\ManageRecords;

class ManageBtbLc extends ManageRecords
{
    protected static string $resource = BtbLcResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
