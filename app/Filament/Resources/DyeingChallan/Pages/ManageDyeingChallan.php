<?php

namespace App\Filament\Resources\DyeingChallan\Pages;

use App\Filament\Resources\DyeingChallanResource;
use Filament\Resources\Pages\ManageRecords;

class ManageDyeingChallan extends ManageRecords
{
    protected static string $resource = DyeingChallanResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
