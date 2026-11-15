<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LabTest\Pages\ManageLabTest;
use App\Models\LabTest;
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

class LabTestResource extends Resource
{
    protected static ?string $model = LabTest::class;

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
                Select::make('style_id')->relationship('style', 'name')->searchable()->preload(),
                Select::make('wash_lot_id')->relationship('washLot', 'number')->searchable()->preload(),
                TextInput::make('test_type')->required(),
                TextInput::make('standard'),
                TextInput::make('result_value'),
                Toggle::make('passed'),
                TextInput::make('report_path'),
                DatePicker::make('expires_on'),
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
                TextColumn::make('wash_lot_id'),
                TextColumn::make('test_type')->searchable(),
                TextColumn::make('standard')->searchable(),
                TextColumn::make('result_value')->searchable(),
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
                    $filename = 'lab_tests.csv';

                    return response()->streamDownload(function (): void {
                        $handle = fopen('php://output', 'w');
                        $first = LabTest::query()->first();
                        if (! $first) {
                            fclose($handle);

                            return;
                        }
                        fputcsv($handle, array_keys($first->getAttributes()));
                        LabTest::query()->orderByDesc('id')->limit(5000)->chunk(200, function ($rows) use ($handle): void {
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
            'index' => ManageLabTest::route('/'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['number'];
    }
}
