<?php

namespace App\Filament\Resources\AutomationRule\Pages;

use App\Filament\Resources\AutomationRuleResource;
use Filament\Resources\Pages\ManageRecords;

class ManageAutomationRule extends ManageRecords
{
    protected static string $resource = AutomationRuleResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
