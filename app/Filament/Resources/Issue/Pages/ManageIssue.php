<?php

namespace App\Filament\Resources\Issue\Pages;

use App\Filament\Resources\IssueResource;
use Filament\Resources\Pages\ManageRecords;

class ManageIssue extends ManageRecords
{
    protected static string $resource = IssueResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
