<?php

namespace App\Filament\Resources;

use App\Filament\Resources\YarnInspection\Pages\ManageYarnInspection;
use App\Models\YarnInspection;
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

class YarnInspectionResource extends Resource
{
    protected static ?string $model = YarnInspection::class;

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
                Select::make('grn_line_id')->relationship('grnLine', 'id')->searchable()->preload()->required(),
                Toggle::make('count_ok'),
                Toggle::make('strength_ok'),
                Toggle::make('hairiness_ok'),
                Toggle::make('neps_ok'),
                Toggle::make('shade_ok'),
                Toggle::make('cone_weight_ok'),
                Toggle::make('wax_ok'),
                TextInput::make('decision')->required(),
                Textarea::make('notes')->columnSpanFull(),
                Hidden::make('lock_version'),
                Select::make('branch_id')->relationship('branch', 'name')->searchable()->preload(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('grn_line_id'),
                TextColumn::make('count_ok'),
                TextColumn::make('strength_ok'),
                TextColumn::make('hairiness_ok'),
                TextColumn::make('neps_ok'),
                TextColumn::make('shade_ok'),
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
                    $filename = 'yarn_inspections.csv';

                    return response()->streamDownload(function (): void {
                        $handle = fopen('php://output', 'w');
                        $first = YarnInspection::query()->first();
                        if (! $first) {
                            fclose($handle);

                            return;
                        }
                        fputcsv($handle, array_keys($first->getAttributes()));
                        YarnInspection::query()->orderByDesc('id')->limit(5000)->chunk(200, function ($rows) use ($handle): void {
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
            'index' => ManageYarnInspection::route('/'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [];
    }
}
