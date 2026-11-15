<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HourlyProduction\Pages\ManageHourlyProduction;
use App\Models\HourlyProduction;
use App\Support\Modules\ModuleRegistry;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Symfony\Component\HttpFoundation\StreamedResponse;

class HourlyProductionResource extends Resource
{
    protected static ?string $model = HourlyProduction::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Sewing';

    protected static ?string $moduleKey = 'cutting';

    public static function canViewAny(): bool
    {
        if (static::$moduleKey && ! app(ModuleRegistry::class)->enabled(static::$moduleKey)) {
            return false;
        }

        return parent::canViewAny();
    }

    public static function canCreate(): bool
    {
        return true && parent::canCreate();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
                Select::make('sewing_line_id')->relationship('sewingLine', 'name')->searchable()->preload()->required(),
                Select::make('production_order_id')->relationship('productionOrder', 'number')->searchable()->preload(),
                DateTimePicker::make('hour_at')->required(),
                TextInput::make('output_qty')->numeric()->required(),
                TextInput::make('dhu')->numeric()->required(),
                Hidden::make('lock_version'),
                Select::make('branch_id')->relationship('branch', 'name')->searchable()->preload(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sewing_line_id'),
                TextColumn::make('production_order_id'),
                TextColumn::make('hour_at'),
                TextColumn::make('output_qty'),
                TextColumn::make('dhu'),
                TextColumn::make('branch.name')->label('Branch'),
            ])
            ->filters([
                TrashedFilter::make(),
                
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
            ])
            ->headerActions([
                Action::make('exportCsv')->label('Export CSV')->action(function (): StreamedResponse {
                    $filename = 'hourly_productions.csv';

                    return response()->streamDownload(function (): void {
                        $handle = fopen('php://output', 'w');
                        $first = HourlyProduction::query()->first();
                        if (! $first) {
                            fclose($handle);

                            return;
                        }
                        fputcsv($handle, array_keys($first->getAttributes()));
                        HourlyProduction::query()->orderByDesc('id')->limit(5000)->chunk(200, function ($rows) use ($handle): void {
                            foreach ($rows as $row) {
                                fputcsv($handle, $row->getAttributes());
                            }
                        });
                        fclose($handle);
                    }, $filename);
                }),
            ])
            ->defaultSort('id', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageHourlyProduction::route('/'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [];
    }
}
