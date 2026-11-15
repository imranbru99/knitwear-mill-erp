<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SampleOrder\Pages\ManageSampleOrder;
use App\Models\SampleOrder;
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

class SampleOrderResource extends Resource
{
    protected static ?string $model = SampleOrder::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Sampling';

    protected static ?string $moduleKey = 'sampling';

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
                Select::make('buyer_id')->relationship('buyer', 'name')->searchable()->preload(),
                TextInput::make('type')->required(),
                TextInput::make('status')->required(),
                DatePicker::make('requested_on'),
                DatePicker::make('promised_on'),
                TextInput::make('yarn_grams')->numeric()->required(),
                TextInput::make('revision')->numeric()->required(),
                TextInput::make('courier_awb'),
                TextInput::make('cost')->numeric()->required(),
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
                TextColumn::make('buyer_id'),
                TextColumn::make('type')->searchable(),
                TextColumn::make('status')->searchable(),
                TextColumn::make('requested_on'),
            ])
            ->filters([
                SelectFilter::make('status'),
                TrashedFilter::make(),
                
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
                Action::make('print')->label('Print')->url(fn ($record) => route('documents.print', ['type' => $record->getTable(), 'id' => $record->getKey()]))->openUrlInNewTab(),
                Action::make('raise')->label('Raise')->requiresConfirmation()->action(function ($record) {
                    app(\App\Services\Workflow\DocumentWorkflow::class)->raise($record);
                    \Filament\Notifications\Notification::make()->title('Raise completed')->success()->send();
                })
            ])
            ->headerActions([
                Action::make('exportCsv')->label('Export CSV')->action(function (): StreamedResponse {
                    $filename = 'sample_orders.csv';

                    return response()->streamDownload(function (): void {
                        $handle = fopen('php://output', 'w');
                        $first = SampleOrder::query()->first();
                        if (! $first) {
                            fclose($handle);

                            return;
                        }
                        fputcsv($handle, array_keys($first->getAttributes()));
                        SampleOrder::query()->orderByDesc('id')->limit(5000)->chunk(200, function ($rows) use ($handle): void {
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
            'index' => ManageSampleOrder::route('/'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['number'];
    }
}
