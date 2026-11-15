<?php

namespace App\Filament\Resources\SampleComment\Pages;

use App\Filament\Resources\SampleCommentResource;
use Filament\Resources\Pages\ManageRecords;

class ManageSampleComment extends ManageRecords
{
    protected static string $resource = SampleCommentResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
