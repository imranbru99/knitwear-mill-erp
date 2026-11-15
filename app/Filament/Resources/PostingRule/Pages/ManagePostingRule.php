<?php

namespace App\Filament\Resources\PostingRule\Pages;

use App\Filament\Resources\PostingRuleResource;
use Filament\Resources\Pages\ManageRecords;

class ManagePostingRule extends ManageRecords
{
    protected static string $resource = PostingRuleResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
