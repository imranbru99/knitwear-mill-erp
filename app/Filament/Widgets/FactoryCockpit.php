<?php

namespace App\Filament\Widgets;

use App\Models\BuyerOrder;
use App\Models\Issue;
use App\Models\ProductionOrder;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class FactoryCockpit extends StatsOverviewWidget
{
    protected ?string $heading = 'Mill cockpit';

    protected function getStats(): array
    {
        return [
            Stat::make('Orders on hand', (string) BuyerOrder::query()->whereIn('status', ['confirmed', 'in_production'])->count()),
            Stat::make('Ship-date risk', (string) ProductionOrder::query()->whereIn('risk', ['amber', 'red'])->count()),
            Stat::make('Open issues', (string) Issue::query()->where('status', 'open')->count()),
            Stat::make('Chronic issues', (string) Issue::query()->where('chronic', true)->where('status', 'open')->count()),
        ];
    }
}
