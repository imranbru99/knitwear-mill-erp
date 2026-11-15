<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Issue\Pages\ManageIssue;
use App\Models\Issue;
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

class IssueResource extends Resource
{
    protected static ?string $model = Issue::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Issues';

    protected static ?string $moduleKey = 'issues';

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
                Select::make('issue_type_id')->relationship('issueType', 'name')->searchable()->preload()->required(),
                TextInput::make('severity')->required(),
                TextInput::make('module')->required(),
                TextInput::make('title')->required(),
                Textarea::make('description')->columnSpanFull(),
                TextInput::make('source_type'),
                TextInput::make('source_id')->numeric(),
                Select::make('production_order_id')->relationship('productionOrder', 'number')->searchable()->preload(),
                Select::make('machine_id')->relationship('machine', 'name')->searchable()->preload(),
                Select::make('supplier_id')->relationship('supplier', 'name')->searchable()->preload(),
                Select::make('department_id')->relationship('department', 'name')->searchable()->preload(),
                Select::make('owner_id')->relationship('owner', 'name')->searchable()->preload(),
                TextInput::make('yarn_lot'),
                TextInput::make('defect_code'),
                DateTimePicker::make('due_at'),
                TextInput::make('status')->required(),
                DateTimePicker::make('sla_due_at'),
                Textarea::make('root_cause')->columnSpanFull(),
                Textarea::make('why_1')->columnSpanFull(),
                Textarea::make('why_2')->columnSpanFull(),
                Textarea::make('why_3')->columnSpanFull(),
                Textarea::make('why_4')->columnSpanFull(),
                Textarea::make('why_5')->columnSpanFull(),
                Textarea::make('corrective_action')->columnSpanFull(),
                Textarea::make('preventive_action')->columnSpanFull(),
                DateTimePicker::make('verified_at'),
                TextInput::make('cost_impact')->numeric()->required(),
                TextInput::make('time_impact_minutes')->numeric()->required(),
                Textarea::make('ai_summary')->columnSpanFull(),
                Select::make('parent_id')->relationship('parent', 'title')->searchable()->preload(),
                Toggle::make('chronic'),
                TextInput::make('escalation_level')->numeric()->required(),
                Hidden::make('lock_version'),
                Select::make('branch_id')->relationship('branch', 'name')->searchable()->preload(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('number')->searchable(),
                TextColumn::make('issue_type_id'),
                TextColumn::make('severity')->searchable(),
                TextColumn::make('module')->searchable(),
                TextColumn::make('title')->searchable(),
                TextColumn::make('source_type')->searchable(),
            ])
            ->filters([
                SelectFilter::make('status'),
                TrashedFilter::make(),
                
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
                Action::make('close')->label('Close')->requiresConfirmation()->action(function ($record) {
                    app(\App\Services\Workflow\DocumentWorkflow::class)->close($record);
                    \Filament\Notifications\Notification::make()->title('Close completed')->success()->send();
                }),
                Action::make('raise')->label('Raise')->requiresConfirmation()->action(function ($record) {
                    app(\App\Services\Workflow\DocumentWorkflow::class)->raise($record);
                    \Filament\Notifications\Notification::make()->title('Raise completed')->success()->send();
                })
            ])
            ->headerActions([
                Action::make('exportCsv')->label('Export CSV')->action(function (): StreamedResponse {
                    $filename = 'issues.csv';

                    return response()->streamDownload(function (): void {
                        $handle = fopen('php://output', 'w');
                        $first = Issue::query()->first();
                        if (! $first) {
                            fclose($handle);

                            return;
                        }
                        fputcsv($handle, array_keys($first->getAttributes()));
                        Issue::query()->orderByDesc('id')->limit(5000)->chunk(200, function ($rows) use ($handle): void {
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
            'index' => ManageIssue::route('/'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['number', 'title'];
    }
}
