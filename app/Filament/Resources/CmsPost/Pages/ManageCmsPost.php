<?php

namespace App\Filament\Resources\CmsPost\Pages;

use App\Filament\Resources\CmsPostResource;
use Filament\Resources\Pages\ManageRecords;

class ManageCmsPost extends ManageRecords
{
    protected static string $resource = CmsPostResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
