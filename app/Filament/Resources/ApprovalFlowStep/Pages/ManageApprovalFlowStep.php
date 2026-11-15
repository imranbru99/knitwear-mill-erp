<?php

namespace App\Filament\Resources\ApprovalFlowStep\Pages;

use App\Filament\Resources\ApprovalFlowStepResource;
use Filament\Resources\Pages\ManageRecords;

class ManageApprovalFlowStep extends ManageRecords
{
    protected static string $resource = ApprovalFlowStepResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
