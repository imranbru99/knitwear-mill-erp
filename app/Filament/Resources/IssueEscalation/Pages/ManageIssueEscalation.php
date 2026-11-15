<?php

namespace App\Filament\Resources\IssueEscalation\Pages;

use App\Filament\Resources\IssueEscalationResource;
use Filament\Resources\Pages\ManageRecords;

class ManageIssueEscalation extends ManageRecords
{
    protected static string $resource = IssueEscalationResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
