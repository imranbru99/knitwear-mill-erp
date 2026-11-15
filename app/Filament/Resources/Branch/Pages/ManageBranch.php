<?php

namespace App\Filament\Resources\Branch\Pages;

use App\Filament\Resources\BranchResource;
use Filament\Resources\Pages\ManageRecords;

class ManageBranch extends ManageRecords
{
    protected static string $resource = BranchResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
