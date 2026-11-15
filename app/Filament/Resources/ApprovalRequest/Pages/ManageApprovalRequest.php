<?php

namespace App\Filament\Resources\ApprovalRequest\Pages;

use App\Filament\Resources\ApprovalRequestResource;
use Filament\Resources\Pages\ManageRecords;

class ManageApprovalRequest extends ManageRecords
{
    protected static string $resource = ApprovalRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
