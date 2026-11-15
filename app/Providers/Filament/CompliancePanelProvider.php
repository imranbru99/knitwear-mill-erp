<?php

namespace App\Providers\Filament;

use App\Filament\PanelBuilder;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;

class CompliancePanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return PanelBuilder::make($panel, 'compliance', 'compliance', Color::Slate);
    }
}
