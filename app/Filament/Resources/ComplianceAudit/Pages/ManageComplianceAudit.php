<?php

namespace App\Filament\Resources\ComplianceAudit\Pages;

use App\Filament\Resources\ComplianceAuditResource;
use Filament\Resources\Pages\ManageRecords;

class ManageComplianceAudit extends ManageRecords
{
    protected static string $resource = ComplianceAuditResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
