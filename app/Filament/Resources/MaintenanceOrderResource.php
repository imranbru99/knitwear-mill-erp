<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MaintenanceOrder\Pages\ManageMaintenanceOrder;
use App\Models\MaintenanceOrder;
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

class MaintenanceOrderResource extends Resource
{
    protected static ?string $model = MaintenanceOrder::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Maintenance';

    protected static ?string $moduleKey = 'maintenance';

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
                TextInput::make('number')->required(),
                Select::make('asset_id')->relationship('asset', 'name')->searchable()->preload()->required(),
                TextInput::make('type')->required(),
                TextInput::make('status')->required(),
                DateTimePicker::make('started_at'),
                DateTimePicker::make('finished_at'),
                TextInput::make('cost')->numeric()->required(),
                Textarea::make('note')->columnSpanFull(),
                Hidden::make('lock_version'),
                Select::make('branch_id')->relationship('branch', 'name')->searchable()->preload(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('number')->searchable(),
                TextColumn::make('asset_id'),
                TextColumn::make('type')->searchable(),
                TextColumn::make('status')->searchable(),
                TextColumn::make('started_at'),
                TextColumn::make('finished_at'),
            ])
            ->filters([
                SelectFilter::make('status'),
                TrashedFilter::make(),
                
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
            ])
            ->headerActions([
                Action::make('exportCsv')->label('Export CSV')->action(function (): StreamedResponse {
                    $filename = 'maintenance_orders.csv';

                    return response()->streamDownload(function (): void {
                        $handle = fopen('php://output', 'w');
                        $first = MaintenanceOrder::query()->first();
                        if (! $first) {
                            fclose($handle);

                            return;
                        }
                        fputcsv($handle, array_keys($first->getAttributes()));
                        MaintenanceOrder::query()->orderByDesc('id')->limit(5000)->chunk(200, function ($rows) use ($handle): void {
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
            'index' => ManageMaintenanceOrder::route('/'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['number'];
    }
}
