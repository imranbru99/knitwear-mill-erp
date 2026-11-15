<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Employee\Pages\ManageEmployee;
use App\Models\Employee;
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

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static string|\UnitEnum|null $navigationGroup = 'HR';

    protected static ?string $moduleKey = 'hr';

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
                Select::make('department_id')->relationship('department', 'name')->searchable()->preload(),
                TextInput::make('designation'),
                TextInput::make('grade'),
                TextInput::make('line_name'),
                TextInput::make('skill'),
                DatePicker::make('join_date'),
                TextInput::make('status')->required(),
                TextInput::make('wage_type')->required(),
                TextInput::make('basic_salary')->numeric()->required(),
                TextInput::make('piece_rate')->numeric()->required(),
                TextInput::make('bank_account'),
                TextInput::make('nid'),
                Select::make('user_id')->relationship('user', 'name')->searchable()->preload(),
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
                TextColumn::make('department_id'),
                TextColumn::make('designation')->searchable(),
                TextColumn::make('grade')->searchable(),
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
                    $filename = 'employees.csv';

                    return response()->streamDownload(function (): void {
                        $handle = fopen('php://output', 'w');
                        $first = Employee::query()->first();
                        if (! $first) {
                            fclose($handle);

                            return;
                        }
                        fputcsv($handle, array_keys($first->getAttributes()));
                        Employee::query()->orderByDesc('id')->limit(5000)->chunk(200, function ($rows) use ($handle): void {
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
            'index' => ManageEmployee::route('/'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['code', 'name'];
    }
}
