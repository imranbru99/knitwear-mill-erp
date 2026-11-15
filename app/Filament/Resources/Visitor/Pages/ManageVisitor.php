<?php

namespace App\Filament\Resources\Visitor\Pages;

use App\Filament\Resources\VisitorResource;
use Filament\Resources\Pages\ManageRecords;

class ManageVisitor extends ManageRecords
{
    protected static string $resource = VisitorResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
