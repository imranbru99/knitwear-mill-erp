<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductionEntry\Pages\ManageProductionEntry;
use App\Models\ProductionEntry;
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

class ProductionEntryResource extends Resource
{
    protected static ?string $model = ProductionEntry::class;

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
                Select::make('bundle_id')->relationship('bundle', 'number')->searchable()->preload(),
                Select::make('machine_id')->relationship('machine', 'name')->searchable()->preload(),
                Select::make('operator_id')->relationship('operator', 'name')->searchable()->preload(),
                TextInput::make('shift')->required(),
                TextInput::make('pieces')->numeric()->required(),
                TextInput::make('waste_grams')->numeric()->required(),
                TextInput::make('minutes')->numeric()->required(),
                DateTimePicker::make('recorded_at')->required(),
                Hidden::make('lock_version'),
                Select::make('branch_id')->relationship('branch', 'name')->searchable()->preload(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('bundle_id'),
                TextColumn::make('machine_id'),
                TextColumn::make('operator_id'),
                TextColumn::make('shift')->searchable(),
                TextColumn::make('pieces'),
                TextColumn::make('waste_grams'),
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
                    $filename = 'production_entries.csv';

                    return response()->streamDownload(function (): void {
                        $handle = fopen('php://output', 'w');
                        $first = ProductionEntry::query()->first();
                        if (! $first) {
                            fclose($handle);

                            return;
                        }
                        fputcsv($handle, array_keys($first->getAttributes()));
                        ProductionEntry::query()->orderByDesc('id')->limit(5000)->chunk(200, function ($rows) use ($handle): void {
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
            'index' => ManageProductionEntry::route('/'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [];
    }
}
