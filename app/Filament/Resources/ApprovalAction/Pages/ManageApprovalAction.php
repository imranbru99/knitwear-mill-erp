<?php

namespace App\Filament\Resources\ApprovalAction\Pages;

use App\Filament\Resources\ApprovalActionResource;
use Filament\Resources\Pages\ManageRecords;

class ManageApprovalAction extends ManageRecords
{
    protected static string $resource = ApprovalActionResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
