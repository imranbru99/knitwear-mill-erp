<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MaterialRequirement\Pages\ManageMaterialRequirement;
use App\Models\MaterialRequirement;
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

class MaterialRequirementResource extends Resource
{
    protected static ?string $model = MaterialRequirement::class;

    protected static string|\UnitEnum|null $navigationGroup = 'BOM';

    protected static ?string $moduleKey = 'bom';

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
                TextInput::make('item_type')->required(),
                TextInput::make('description')->required(),
                TextInput::make('uom')->required(),
                TextInput::make('required_qty')->numeric()->required(),
                TextInput::make('available_qty')->numeric()->required(),
                TextInput::make('incoming_qty')->numeric()->required(),
                TextInput::make('reserved_qty')->numeric()->required(),
                TextInput::make('shortfall_qty')->numeric()->required(),
                TextInput::make('status')->required(),
                Hidden::make('lock_version'),
                Select::make('branch_id')->relationship('branch', 'name')->searchable()->preload(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('production_order_id'),
                TextColumn::make('item_type')->searchable(),
                TextColumn::make('description')->searchable(),
                TextColumn::make('uom')->searchable(),
                TextColumn::make('required_qty'),
                TextColumn::make('available_qty'),
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
                    $filename = 'material_requirements.csv';

                    return response()->streamDownload(function (): void {
                        $handle = fopen('php://output', 'w');
                        $first = MaterialRequirement::query()->first();
                        if (! $first) {
                            fclose($handle);

                            return;
                        }
                        fputcsv($handle, array_keys($first->getAttributes()));
                        MaterialRequirement::query()->orderByDesc('id')->limit(5000)->chunk(200, function ($rows) use ($handle): void {
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
            'index' => ManageMaterialRequirement::route('/'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [];
    }
}
