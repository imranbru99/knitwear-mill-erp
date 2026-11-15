<?php

namespace App\Filament\Resources\ChartOfAccount\Pages;

use App\Filament\Resources\ChartOfAccountResource;
use Filament\Resources\Pages\ManageRecords;

class ManageChartOfAccount extends ManageRecords
{
    protected static string $resource = ChartOfAccountResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
