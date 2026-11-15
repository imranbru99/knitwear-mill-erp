<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PurchaseOrder\Pages\ManagePurchaseOrder;
use App\Models\PurchaseOrder;
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

class PurchaseOrderResource extends Resource
{
    protected static ?string $model = PurchaseOrder::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Purchase';

    protected static ?string $moduleKey = 'purchase';

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
                Select::make('supplier_id')->relationship('supplier', 'name')->searchable()->preload()->required(),
                Select::make('requisition_id')->relationship('requisition', 'number')->searchable()->preload(),
                TextInput::make('currency')->required(),
                TextInput::make('total')->numeric()->required(),
                TextInput::make('status')->required(),
                DatePicker::make('expected_on'),
                Textarea::make('notes')->columnSpanFull(),
                Hidden::make('lock_version'),
                Select::make('branch_id')->relationship('branch', 'name')->searchable()->preload(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('number')->searchable(),
                TextColumn::make('supplier_id'),
                TextColumn::make('requisition_id'),
                TextColumn::make('currency')->searchable(),
                TextColumn::make('total'),
                TextColumn::make('status')->searchable(),
            ])
            ->filters([
                SelectFilter::make('status'),
                TrashedFilter::make(),
                
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
                Action::make('approve')->label('Approve')->requiresConfirmation()->action(function ($record) {
                    app(\App\Services\Workflow\DocumentWorkflow::class)->approve($record);
                    \Filament\Notifications\Notification::make()->title('Approve completed')->success()->send();
                }),
                Action::make('print')->label('Print')->url(fn ($record) => route('documents.print', ['type' => $record->getTable(), 'id' => $record->getKey()]))->openUrlInNewTab(),
                Action::make('raise')->label('Raise')->requiresConfirmation()->action(function ($record) {
                    app(\App\Services\Workflow\DocumentWorkflow::class)->raise($record);
                    \Filament\Notifications\Notification::make()->title('Raise completed')->success()->send();
                })
            ])
            ->headerActions([
                Action::make('exportCsv')->label('Export CSV')->action(function (): StreamedResponse {
                    $filename = 'purchase_orders.csv';

                    return response()->streamDownload(function (): void {
                        $handle = fopen('php://output', 'w');
                        $first = PurchaseOrder::query()->first();
                        if (! $first) {
                            fclose($handle);

                            return;
                        }
                        fputcsv($handle, array_keys($first->getAttributes()));
                        PurchaseOrder::query()->orderByDesc('id')->limit(5000)->chunk(200, function ($rows) use ($handle): void {
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
            'index' => ManagePurchaseOrder::route('/'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['number'];
    }
}
