<?php

namespace App\Filament\Resources\SampleStage\Pages;

use App\Filament\Resources\SampleStageResource;
use Filament\Resources\Pages\ManageRecords;

class ManageSampleStage extends ManageRecords
{
    protected static string $resource = SampleStageResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
