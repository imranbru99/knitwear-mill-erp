<?php

namespace App\Filament\Resources\IssueType\Pages;

use App\Filament\Resources\IssueTypeResource;
use Filament\Resources\Pages\ManageRecords;

class ManageIssueType extends ManageRecords
{
    protected static string $resource = IssueTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
