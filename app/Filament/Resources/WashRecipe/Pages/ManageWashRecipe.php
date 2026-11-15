<?php

namespace App\Filament\Resources\WashRecipe\Pages;

use App\Filament\Resources\WashRecipeResource;
use Filament\Resources\Pages\ManageRecords;

class ManageWashRecipe extends ManageRecords
{
    protected static string $resource = WashRecipeResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
