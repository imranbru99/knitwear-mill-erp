<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Inspection\Pages\ManageInspection;
use App\Models\Inspection;
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

class InspectionResource extends Resource
{
    protected static ?string $model = Inspection::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Quality';

    protected static ?string $moduleKey = 'quality';

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
                Select::make('production_order_id')->relationship('productionOrder', 'number')->searchable()->preload()->required(),
                TextInput::make('type')->required(),
                TextInput::make('aql_major')->numeric()->required(),
                TextInput::make('aql_minor')->numeric()->required(),
                TextInput::make('lot_size')->numeric()->required(),
                TextInput::make('sample_size')->numeric()->required(),
                TextInput::make('major_found')->numeric()->required(),
                TextInput::make('minor_found')->numeric()->required(),
                TextInput::make('critical_found')->numeric()->required(),
                TextInput::make('major_accept')->numeric()->required(),
                TextInput::make('result'),
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
                TextColumn::make('production_order_id'),
                TextColumn::make('type')->searchable(),
                TextColumn::make('aql_major'),
                TextColumn::make('aql_minor'),
                TextColumn::make('lot_size'),
            ])
            ->filters([
                SelectFilter::make('status'),
                TrashedFilter::make(),
                
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
                Action::make('judge')->label('Judge')->requiresConfirmation()->action(function ($record) {
                    app(\App\Services\Workflow\DocumentWorkflow::class)->judge($record);
                    \Filament\Notifications\Notification::make()->title('Judge completed')->success()->send();
                }),
                Action::make('print')->label('Print')->url(fn ($record) => route('documents.print', ['type' => $record->getTable(), 'id' => $record->getKey()]))->openUrlInNewTab(),
                Action::make('raise')->label('Raise')->requiresConfirmation()->action(function ($record) {
                    app(\App\Services\Workflow\DocumentWorkflow::class)->raise($record);
                    \Filament\Notifications\Notification::make()->title('Raise completed')->success()->send();
                })
            ])
            ->headerActions([
                Action::make('exportCsv')->label('Export CSV')->action(function (): StreamedResponse {
                    $filename = 'inspections.csv';

                    return response()->streamDownload(function (): void {
                        $handle = fopen('php://output', 'w');
                        $first = Inspection::query()->first();
                        if (! $first) {
                            fclose($handle);

                            return;
                        }
                        fputcsv($handle, array_keys($first->getAttributes()));
                        Inspection::query()->orderByDesc('id')->limit(5000)->chunk(200, function ($rows) use ($handle): void {
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
            'index' => ManageInspection::route('/'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['number'];
    }
}
