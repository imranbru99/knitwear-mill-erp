<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BuyerOrder\Pages\ManageBuyerOrder;
use App\Models\BuyerOrder;
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

class BuyerOrderResource extends Resource
{
    protected static ?string $model = BuyerOrder::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Orders';

    protected static ?string $moduleKey = 'orders';

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
                Select::make('buyer_id')->relationship('buyer', 'name')->searchable()->preload()->required(),
                Select::make('style_id')->relationship('style', 'name')->searchable()->preload()->required(),
                TextInput::make('buyer_po_no')->required(),
                TextInput::make('currency')->required(),
                TextInput::make('quantity')->numeric()->required(),
                DatePicker::make('ex_factory_date')->required(),
                DatePicker::make('ship_date')->required(),
                TextInput::make('port'),
                TextInput::make('mode')->required(),
                TextInput::make('incoterm'),
                TextInput::make('status')->required(),
                TextInput::make('production_route')->required(),
                TextInput::make('revision')->numeric()->required(),
                TextInput::make('unit_price')->numeric()->required(),
                Hidden::make('lock_version'),
                Select::make('branch_id')->relationship('branch', 'name')->searchable()->preload(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('number')->searchable(),
                TextColumn::make('buyer_id'),
                TextColumn::make('style_id'),
                TextColumn::make('buyer_po_no')->searchable(),
                TextColumn::make('currency')->searchable(),
                TextColumn::make('quantity'),
            ])
            ->filters([
                SelectFilter::make('status'),
                TrashedFilter::make(),
                
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
                Action::make('confirm')->label('Confirm')->requiresConfirmation()->action(function ($record) {
                    app(\App\Services\Workflow\DocumentWorkflow::class)->confirm($record);
                    \Filament\Notifications\Notification::make()->title('Confirm completed')->success()->send();
                }),
                Action::make('print')->label('Print')->url(fn ($record) => route('documents.print', ['type' => $record->getTable(), 'id' => $record->getKey()]))->openUrlInNewTab(),
                Action::make('raise')->label('Raise')->requiresConfirmation()->action(function ($record) {
                    app(\App\Services\Workflow\DocumentWorkflow::class)->raise($record);
                    \Filament\Notifications\Notification::make()->title('Raise completed')->success()->send();
                })
            ])
            ->headerActions([
                Action::make('exportCsv')->label('Export CSV')->action(function (): StreamedResponse {
                    $filename = 'buyer_orders.csv';

                    return response()->streamDownload(function (): void {
                        $handle = fopen('php://output', 'w');
                        $first = BuyerOrder::query()->first();
                        if (! $first) {
                            fclose($handle);

                            return;
                        }
                        fputcsv($handle, array_keys($first->getAttributes()));
                        BuyerOrder::query()->orderByDesc('id')->limit(5000)->chunk(200, function ($rows) use ($handle): void {
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
            'index' => ManageBuyerOrder::route('/'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['number', 'buyer_po_no'];
    }
}
