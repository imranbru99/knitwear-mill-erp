<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Bundle\Pages\ManageBundle;
use App\Models\Bundle;
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

class BundleResource extends Resource
{
    protected static ?string $model = Bundle::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Knitting';

    protected static ?string $moduleKey = 'knitting';

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
                TextInput::make('barcode')->required(),
                Select::make('production_order_id')->relationship('productionOrder', 'number')->searchable()->preload()->required(),
                Select::make('style_id')->relationship('style', 'name')->searchable()->preload()->required(),
                TextInput::make('colour')->required(),
                TextInput::make('size_code')->required(),
                TextInput::make('quantity')->numeric()->required(),
                Select::make('machine_id')->relationship('machine', 'name')->searchable()->preload(),
                Select::make('operator_id')->relationship('operator', 'name')->searchable()->preload(),
                TextInput::make('stage')->required(),
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
                TextColumn::make('barcode')->searchable(),
                TextColumn::make('production_order_id'),
                TextColumn::make('style_id'),
                TextColumn::make('colour')->searchable(),
                TextColumn::make('size_code')->searchable(),
            ])
            ->filters([
                SelectFilter::make('status'),
                TrashedFilter::make(),
                
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
                Action::make('print')->label('Print')->url(fn ($record) => route('documents.print', ['type' => $record->getTable(), 'id' => $record->getKey()]))->openUrlInNewTab()
            ])
            ->headerActions([
                Action::make('exportCsv')->label('Export CSV')->action(function (): StreamedResponse {
                    $filename = 'bundles.csv';

                    return response()->streamDownload(function (): void {
                        $handle = fopen('php://output', 'w');
                        $first = Bundle::query()->first();
                        if (! $first) {
                            fclose($handle);

                            return;
                        }
                        fputcsv($handle, array_keys($first->getAttributes()));
                        Bundle::query()->orderByDesc('id')->limit(5000)->chunk(200, function ($rows) use ($handle): void {
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
            'index' => ManageBundle::route('/'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['number'];
    }
}
