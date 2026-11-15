<?php

namespace App\Filament\Resources\ApprovalFlow\Pages;

use App\Filament\Resources\ApprovalFlowResource;
use Filament\Resources\Pages\ManageRecords;

class ManageApprovalFlow extends ManageRecords
{
    protected static string $resource = ApprovalFlowResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
