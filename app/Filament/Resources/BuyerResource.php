<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Buyer\Pages\ManageBuyer;
use App\Models\Buyer;
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

class BuyerResource extends Resource
{
    protected static ?string $model = Buyer::class;

    protected static string|\UnitEnum|null $navigationGroup = 'CRM';

    protected static ?string $moduleKey = 'crm';

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
                TextInput::make('country'),
                TextInput::make('agent_name'),
                TextInput::make('payment_terms'),
                TextInput::make('incoterm'),
                TextInput::make('currency')->required(),
                TextInput::make('default_aql_major')->numeric()->required(),
                TextInput::make('default_aql_minor')->numeric()->required(),
                Textarea::make('labeling_rules')->columnSpanFull(),
                Textarea::make('packing_rules')->columnSpanFull(),
                TextInput::make('credit_limit')->numeric()->required(),
                TextInput::make('status')->required(),
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
                TextColumn::make('country')->searchable(),
                TextColumn::make('agent_name')->searchable(),
                TextColumn::make('payment_terms')->searchable(),
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
                    $filename = 'buyers.csv';

                    return response()->streamDownload(function (): void {
                        $handle = fopen('php://output', 'w');
                        $first = Buyer::query()->first();
                        if (! $first) {
                            fclose($handle);

                            return;
                        }
                        fputcsv($handle, array_keys($first->getAttributes()));
                        Buyer::query()->orderByDesc('id')->limit(5000)->chunk(200, function ($rows) use ($handle): void {
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
            'index' => ManageBuyer::route('/'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['code', 'name'];
    }
}
