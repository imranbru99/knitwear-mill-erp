<?php

namespace App\Filament\Resources\AuditFinding\Pages;

use App\Filament\Resources\AuditFindingResource;
use Filament\Resources\Pages\ManageRecords;

class ManageAuditFinding extends ManageRecords
{
    protected static string $resource = AuditFindingResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
