<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GrnLine\Pages\ManageGrnLine;
use App\Models\GrnLine;
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

class GrnLineResource extends Resource
{
    protected static ?string $model = GrnLine::class;

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
        return true && parent::canCreate();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
                Select::make('grn_id')->relationship('grn', 'number')->searchable()->preload()->required(),
                Select::make('purchase_order_line_id')->relationship('purchaseOrderLine', 'id')->searchable()->preload(),
                TextInput::make('lot_no')->required(),
                TextInput::make('item_code')->required(),
                TextInput::make('qty')->numeric()->required(),
                TextInput::make('uom')->required(),
                TextInput::make('gross_weight')->numeric(),
                TextInput::make('net_weight')->numeric(),
                TextInput::make('moisture')->numeric(),
                TextInput::make('shade'),
                TextInput::make('inspection_status')->required(),
                Hidden::make('lock_version'),
                Select::make('branch_id')->relationship('branch', 'name')->searchable()->preload(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('grn_id'),
                TextColumn::make('purchase_order_line_id'),
                TextColumn::make('lot_no')->searchable(),
                TextColumn::make('item_code')->searchable(),
                TextColumn::make('qty'),
                TextColumn::make('uom')->searchable(),
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
                    $filename = 'grn_lines.csv';

                    return response()->streamDownload(function (): void {
                        $handle = fopen('php://output', 'w');
                        $first = GrnLine::query()->first();
                        if (! $first) {
                            fclose($handle);

                            return;
                        }
                        fputcsv($handle, array_keys($first->getAttributes()));
                        GrnLine::query()->orderByDesc('id')->limit(5000)->chunk(200, function ($rows) use ($handle): void {
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
            'index' => ManageGrnLine::route('/'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [];
    }
}
