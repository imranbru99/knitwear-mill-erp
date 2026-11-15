<?php

namespace App\Filament\Resources\Faq\Pages;

use App\Filament\Resources\FaqResource;
use Filament\Resources\Pages\ManageRecords;

class ManageFaq extends ManageRecords
{
    protected static string $resource = FaqResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
