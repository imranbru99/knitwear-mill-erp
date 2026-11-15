<?php

namespace App\Filament\Resources\Inquiry\Pages;

use App\Filament\Resources\InquiryResource;
use Filament\Resources\Pages\ManageRecords;

class ManageInquiry extends ManageRecords
{
    protected static string $resource = InquiryResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
