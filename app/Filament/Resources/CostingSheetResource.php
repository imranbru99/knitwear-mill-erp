<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CostingSheet\Pages\ManageCostingSheet;
use App\Models\CostingSheet;
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

class CostingSheetResource extends Resource
{
    protected static ?string $model = CostingSheet::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Costing';

    protected static ?string $moduleKey = 'costing';

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
                Select::make('style_id')->relationship('style', 'name')->searchable()->preload()->required(),
                TextInput::make('version')->numeric()->required(),
                TextInput::make('currency')->required(),
                TextInput::make('yarn_cost')->numeric()->required(),
                TextInput::make('knitting_cost')->numeric()->required(),
                TextInput::make('linking_cost')->numeric()->required(),
                TextInput::make('mending_cost')->numeric()->required(),
                TextInput::make('washing_cost')->numeric()->required(),
                TextInput::make('finishing_cost')->numeric()->required(),
                TextInput::make('trims_cost')->numeric()->required(),
                TextInput::make('packing_cost')->numeric()->required(),
                TextInput::make('testing_cost')->numeric()->required(),
                TextInput::make('freight')->numeric()->required(),
                TextInput::make('cm')->numeric()->required(),
                TextInput::make('overhead')->numeric()->required(),
                TextInput::make('finance_cost')->numeric()->required(),
                TextInput::make('commission')->numeric()->required(),
                TextInput::make('profit_percent')->numeric()->required(),
                TextInput::make('total_cost')->numeric()->required(),
                TextInput::make('quoted_price')->numeric()->required(),
                TextInput::make('target_price')->numeric(),
                TextInput::make('status')->required(),
                Hidden::make('lock_version'),
                Select::make('branch_id')->relationship('branch', 'name')->searchable()->preload(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('number')->searchable(),
                TextColumn::make('style_id'),
                TextColumn::make('version'),
                TextColumn::make('currency')->searchable(),
                TextColumn::make('yarn_cost'),
                TextColumn::make('knitting_cost'),
            ])
            ->filters([
                SelectFilter::make('status'),
                TrashedFilter::make(),
                
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
                Action::make('approve')->label('Approve')->requiresConfirmation()->action(function ($record) {
                    app(\App\Services\Workflow\DocumentWorkflow::class)->approve($record);
                    \Filament\Notifications\Notification::make()->title('Approve completed')->success()->send();
                }),
                Action::make('print')->label('Print')->url(fn ($record) => route('documents.print', ['type' => $record->getTable(), 'id' => $record->getKey()]))->openUrlInNewTab()
            ])
            ->headerActions([
                Action::make('exportCsv')->label('Export CSV')->action(function (): StreamedResponse {
                    $filename = 'costing_sheets.csv';

                    return response()->streamDownload(function (): void {
                        $handle = fopen('php://output', 'w');
                        $first = CostingSheet::query()->first();
                        if (! $first) {
                            fclose($handle);

                            return;
                        }
                        fputcsv($handle, array_keys($first->getAttributes()));
                        CostingSheet::query()->orderByDesc('id')->limit(5000)->chunk(200, function ($rows) use ($handle): void {
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
            'index' => ManageCostingSheet::route('/'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['number'];
    }
}
