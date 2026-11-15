<?php

namespace App\Filament\Resources\NumberingSequence\Pages;

use App\Filament\Resources\NumberingSequenceResource;
use Filament\Resources\Pages\ManageRecords;

class ManageNumberingSequence extends ManageRecords
{
    protected static string $resource = NumberingSequenceResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
