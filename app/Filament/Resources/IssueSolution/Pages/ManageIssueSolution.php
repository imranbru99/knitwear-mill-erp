<?php

namespace App\Filament\Resources\IssueSolution\Pages;

use App\Filament\Resources\IssueSolutionResource;
use Filament\Resources\Pages\ManageRecords;

class ManageIssueSolution extends ManageRecords
{
    protected static string $resource = IssueSolutionResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
