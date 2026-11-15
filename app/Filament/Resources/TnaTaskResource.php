<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TnaTask\Pages\ManageTnaTask;
use App\Models\TnaTask;
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

class TnaTaskResource extends Resource
{
    protected static ?string $model = TnaTask::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Orders';

    protected static ?string $moduleKey = 'orders';

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
                Select::make('production_order_id')->relationship('productionOrder', 'number')->searchable()->preload()->required(),
                TextInput::make('name')->required(),
                TextInput::make('department')->required(),
                DatePicker::make('planned_start'),
                DatePicker::make('planned_end')->required(),
                DatePicker::make('actual_end'),
                TextInput::make('depends_on_id')->numeric(),
                TextInput::make('status')->required(),
                Select::make('owner_id')->relationship('owner', 'name')->searchable()->preload(),
                DatePicker::make('baseline_end'),
                TextInput::make('sort')->numeric()->required(),
                Hidden::make('lock_version'),
                Select::make('branch_id')->relationship('branch', 'name')->searchable()->preload(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('production_order_id'),
                TextColumn::make('name')->searchable(),
                TextColumn::make('department')->searchable(),
                TextColumn::make('planned_start'),
                TextColumn::make('planned_end'),
                TextColumn::make('actual_end'),
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
                    $filename = 'tna_tasks.csv';

                    return response()->streamDownload(function (): void {
                        $handle = fopen('php://output', 'w');
                        $first = TnaTask::query()->first();
                        if (! $first) {
                            fclose($handle);

                            return;
                        }
                        fputcsv($handle, array_keys($first->getAttributes()));
                        TnaTask::query()->orderByDesc('id')->limit(5000)->chunk(200, function ($rows) use ($handle): void {
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
            'index' => ManageTnaTask::route('/'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name'];
    }
}
