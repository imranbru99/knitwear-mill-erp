<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StockLedger\Pages\ManageStockLedger;
use App\Models\StockLedger;
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

class StockLedgerResource extends Resource
{
    protected static ?string $model = StockLedger::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Yarn store';

    protected static ?string $moduleKey = 'yarn_store';

    public static function canViewAny(): bool
    {
        if (static::$moduleKey && ! app(ModuleRegistry::class)->enabled(static::$moduleKey)) {
            return false;
        }

        return parent::canViewAny();
    }

    public static function canCreate(): bool
    {
        return false && parent::canCreate();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
                TextInput::make('item_type')->required(),
                TextInput::make('item_code')->required(),
                TextInput::make('lot_no'),
                Select::make('location_id')->relationship('location', 'name')->searchable()->preload(),
                TextInput::make('qty_in')->numeric()->required(),
                TextInput::make('qty_out')->numeric()->required(),
                TextInput::make('uom')->required(),
                TextInput::make('ref_type'),
                TextInput::make('ref_id')->numeric(),
                DateTimePicker::make('happened_at')->required(),
                TextInput::make('note'),
                Hidden::make('lock_version'),
                Select::make('branch_id')->relationship('branch', 'name')->searchable()->preload(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('item_type')->searchable(),
                TextColumn::make('item_code')->searchable(),
                TextColumn::make('lot_no')->searchable(),
                TextColumn::make('location_id'),
                TextColumn::make('qty_in'),
                TextColumn::make('qty_out'),
            ])
            ->filters([
                
            ])
            ->recordActions([
                
            ])
            ->headerActions([
                Action::make('exportCsv')->label('Export CSV')->action(function (): StreamedResponse {
                    $filename = 'stock_ledgers.csv';

                    return response()->streamDownload(function (): void {
                        $handle = fopen('php://output', 'w');
                        $first = StockLedger::query()->first();
                        if (! $first) {
                            fclose($handle);

                            return;
                        }
                        fputcsv($handle, array_keys($first->getAttributes()));
                        StockLedger::query()->orderByDesc('id')->limit(5000)->chunk(200, function ($rows) use ($handle): void {
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
            'index' => ManageStockLedger::route('/'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [];
    }
}
