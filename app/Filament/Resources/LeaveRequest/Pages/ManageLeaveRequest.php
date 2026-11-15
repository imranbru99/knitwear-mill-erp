<?php

namespace App\Filament\Resources\LeaveRequest\Pages;

use App\Filament\Resources\LeaveRequestResource;
use Filament\Resources\Pages\ManageRecords;

class ManageLeaveRequest extends ManageRecords
{
    protected static string $resource = LeaveRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
