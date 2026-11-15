<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VoucherLine\Pages\ManageVoucherLine;
use App\Models\VoucherLine;
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

class VoucherLineResource extends Resource
{
    protected static ?string $model = VoucherLine::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Accounts';

    protected static ?string $moduleKey = 'accounts';

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
                Select::make('voucher_id')->relationship('voucher', 'number')->searchable()->preload()->required(),
                Select::make('account_id')->relationship('account', 'name')->searchable()->preload()->required(),
                TextInput::make('debit')->numeric()->required(),
                TextInput::make('credit')->numeric()->required(),
                Select::make('cost_centre_id')->relationship('costCentre', 'name')->searchable()->preload(),
                Select::make('production_order_id')->relationship('productionOrder', 'number')->searchable()->preload(),
                Hidden::make('lock_version'),
                Select::make('branch_id')->relationship('branch', 'name')->searchable()->preload(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('voucher_id'),
                TextColumn::make('account_id'),
                TextColumn::make('debit'),
                TextColumn::make('credit'),
                TextColumn::make('cost_centre_id'),
                TextColumn::make('production_order_id'),
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
                    $filename = 'voucher_lines.csv';

                    return response()->streamDownload(function (): void {
                        $handle = fopen('php://output', 'w');
                        $first = VoucherLine::query()->first();
                        if (! $first) {
                            fclose($handle);

                            return;
                        }
                        fputcsv($handle, array_keys($first->getAttributes()));
                        VoucherLine::query()->orderByDesc('id')->limit(5000)->chunk(200, function ($rows) use ($handle): void {
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
            'index' => ManageVoucherLine::route('/'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [];
    }
}
