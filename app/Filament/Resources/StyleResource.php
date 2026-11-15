<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Style\Pages\ManageStyle;
use App\Models\Style;
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

class StyleResource extends Resource
{
    protected static ?string $model = Style::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Styles';

    protected static ?string $moduleKey = 'styles';

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
                TextInput::make('code')->required(),
                TextInput::make('name')->required(),
                TextInput::make('name_bn'),
                TextInput::make('slug')->required(),
                Select::make('buyer_id')->relationship('buyer', 'name')->searchable()->preload(),
                Select::make('product_category_id')->relationship('productCategory', 'name')->searchable()->preload(),
                TextInput::make('season'),
                TextInput::make('product_type')->required(),
                TextInput::make('gender'),
                TextInput::make('construction')->required(),
                TextInput::make('production_route')->required(),
                TextInput::make('gauge'),
                TextInput::make('stitch'),
                TextInput::make('composition'),
                TextInput::make('yarn_count'),
                TextInput::make('ply')->numeric(),
                TextInput::make('gsm')->numeric(),
                Textarea::make('description')->columnSpanFull(),
                Textarea::make('wash_care')->columnSpanFull(),
                TextInput::make('packing_method'),
                TextInput::make('status')->required(),
                Textarea::make('dpp')->columnSpanFull(),
                Hidden::make('lock_version'),
                Select::make('branch_id')->relationship('branch', 'name')->searchable()->preload(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')->searchable(),
                TextColumn::make('name')->searchable(),
                TextColumn::make('name_bn')->searchable(),
                TextColumn::make('slug')->searchable(),
                TextColumn::make('buyer_id'),
                TextColumn::make('product_category_id'),
            ])
            ->filters([
                SelectFilter::make('status'),
                TrashedFilter::make(),
                
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
                Action::make('raise')->label('Raise')->requiresConfirmation()->action(function ($record) {
                    app(\App\Services\Workflow\DocumentWorkflow::class)->raise($record);
                    \Filament\Notifications\Notification::make()->title('Raise completed')->success()->send();
                })
            ])
            ->headerActions([
                Action::make('exportCsv')->label('Export CSV')->action(function (): StreamedResponse {
                    $filename = 'styles.csv';

                    return response()->streamDownload(function (): void {
                        $handle = fopen('php://output', 'w');
                        $first = Style::query()->first();
                        if (! $first) {
                            fclose($handle);

                            return;
                        }
                        fputcsv($handle, array_keys($first->getAttributes()));
                        Style::query()->orderByDesc('id')->limit(5000)->chunk(200, function ($rows) use ($handle): void {
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
            'index' => ManageStyle::route('/'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['code', 'name', 'slug'];
    }
}
