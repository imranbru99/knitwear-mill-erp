<?php

namespace App\Filament\Resources\CmsPage\Pages;

use App\Filament\Resources\CmsPageResource;
use Filament\Resources\Pages\ManageRecords;

class ManageCmsPage extends ManageRecords
{
    protected static string $resource = CmsPageResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
