<?php

namespace App\Filament\Resources\ProductCategory\Pages;

use App\Filament\Resources\ProductCategoryResource;
use Filament\Resources\Pages\ManageRecords;

class ManageProductCategory extends ManageRecords
{
    protected static string $resource = ProductCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
