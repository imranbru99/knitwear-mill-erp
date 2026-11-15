<?php

namespace App\Filament\Resources\IssueAction\Pages;

use App\Filament\Resources\IssueActionResource;
use Filament\Resources\Pages\ManageRecords;

class ManageIssueAction extends ManageRecords
{
    protected static string $resource = IssueActionResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
