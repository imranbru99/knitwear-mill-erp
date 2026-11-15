<?php

/**
 * Writes migrations, models, policies and Filament resources from database/schema/knitwear.php.
 * Run: php scripts/generate-domain.php
 */

use Illuminate\Support\Str;

require __DIR__.'/../vendor/autoload.php';

$tables = require __DIR__.'/../database/schema/knitwear.php';

function parseColumn(string $spec): array
{
    $parts = explode(':', $spec);
    $name = array_shift($parts);
    $type = array_shift($parts);
    $nullable = false;
    $unique = false;
    $default = null;
    $hasDefault = false;
    $ref = null;
    $secret = $type === 'secret';
    if ($secret) {
        $type = 'string';
    }
    foreach ($parts as $token) {
        if ($token === 'null') {
            $nullable = true;
            continue;
        }
        if ($token === 'unique') {
            $unique = true;
            continue;
        }
        if ($token === 'index') {
            continue;
        }
        if (str_starts_with($token, 'd=')) {
            $hasDefault = true;
            $default = substr($token, 2);
            continue;
        }
        if ($type === 'foreign' && $ref === null) {
            $ref = $token;
            continue;
        }
        $hasDefault = true;
        $default = $token;
    }

    return compact('name', 'type', 'nullable', 'unique', 'default', 'hasDefault', 'ref', 'secret');
}

function phpString(string $value): string
{
    return "'".str_replace(["\\", "'"], ["\\\\", "\\'"], $value)."'";
}

function titleColumn(array $tables, ?string $ref): string
{
    if ($ref === 'users' || $ref === null) {
        return 'name';
    }
    $cols = array_map(fn ($s) => parseColumn($s)['name'], $tables[$ref]['columns'] ?? []);
    foreach (['name', 'title', 'number', 'code', 'question'] as $candidate) {
        if (in_array($candidate, $cols, true)) {
            return $candidate;
        }
    }

    return 'id';
}

function relationMethod(string $column): string
{
    return match ($column) {
        'assigned_to' => 'assignee',
        'owner_id' => 'owner',
        'operator_id' => 'operator',
        'approved_by' => 'approver',
        'parent_id' => 'parent',
        'depends_on_id' => 'dependsOn',
        'user_id' => 'user',
        'created_by' => 'creator',
        'updated_by' => 'editor',
        default => Str::camel(Str::beforeLast($column, '_id')),
    };
}

function modelClass(array $tables, string $ref): string
{
    if ($ref === 'users') {
        return 'User';
    }

    return $tables[$ref]['model'];
}

$migration = <<<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $foreigns = [];

PHP;

$up = '';
$foreigns = [];

foreach ($tables as $table => $meta) {
    $up .= "        Schema::create('{$table}', function (Blueprint \$table) {\n";
    $up .= "            \$table->id();\n";
    if (! in_array($table, ['modules', 'settings'], true)) {
        $up .= "            \$table->unsignedBigInteger('branch_id')->nullable()->index();\n";
        $foreigns[] = [$table, 'branch_id', 'branches'];
    }
    foreach ($meta['columns'] as $spec) {
        $col = parseColumn($spec);
        $name = $col['name'];
        if ($col['type'] === 'foreign') {
            $line = "            \$table->unsignedBigInteger('{$name}')";
            if ($col['nullable']) {
                $line .= '->nullable()';
            }
            $line .= "->index();\n";
            $up .= $line;
            if ($col['ref']) {
                $foreigns[] = [$table, $name, $col['ref']];
            }
            continue;
        }
        $method = match ($col['type']) {
            'string' => 'string',
            'text' => 'text',
            'bool' => 'boolean',
            'int' => 'integer',
            'decimal' => 'decimal',
            'date' => 'date',
            'datetime' => 'dateTime',
            'json' => 'json',
            default => 'string',
        };
        if ($method === 'decimal') {
            $scale = preg_match('/weight|qty|gram|kg|gsm|moisture|loss|shrink|factor|smv|dhu|length|dia|cbm|ply|percent/', $name) ? 3 : 2;
            $line = "            \$table->decimal('{$name}', 14, {$scale})";
        } else {
            $line = "            \$table->{$method}('{$name}')";
        }
        if ($col['nullable']) {
            $line .= '->nullable()';
        }
        if ($col['hasDefault']) {
            $default = $col['default'];
            if ($col['type'] === 'bool') {
                $line .= '->default('.($default === '1' || $default === 'true' ? 'true' : 'false').')';
            } elseif ($col['type'] === 'int') {
                $line .= '->default('.(int) $default.')';
            } elseif ($col['type'] === 'decimal') {
                $line .= '->default('.(float) $default.')';
            } else {
                $line .= '->default('.phpString((string) $default).')';
            }
        }
        if ($col['unique']) {
            $line .= '->unique()';
        }
        $line .= ";\n";
        $up .= $line;
    }
    $up .= "            \$table->unsignedBigInteger('created_by')->nullable()->index();\n";
    $up .= "            \$table->unsignedBigInteger('updated_by')->nullable()->index();\n";
    $up .= "            \$table->unsignedInteger('lock_version')->default(1);\n";
    $up .= "            \$table->timestamps();\n";
    if ($meta['soft']) {
        $up .= "            \$table->softDeletes();\n";
    }
    $up .= "        });\n\n";
}

$fk = "        if (Schema::getConnection()->getDriverName() !== 'sqlite') {\n";
foreach ($foreigns as [$table, $column, $ref]) {
    if (! isset($tables[$ref]) && $ref !== 'branches' && $ref !== 'users') {
        continue;
    }
    $fk .= "            Schema::table('{$table}', function (Blueprint \$table) {\n";
    $fk .= "                \$table->foreign('{$column}')->references('id')->on('{$ref}')->nullOnDelete();\n";
    $fk .= "            });\n";
}
$fk .= "        }\n";

$down = '';
foreach (array_reverse(array_keys($tables)) as $table) {
    $down .= "        Schema::dropIfExists('{$table}');\n";
}

$migration .= $up.$fk."    }\n\n    public function down(): void\n    {\n".$down."    }\n};\n";

file_put_contents(__DIR__.'/../database/migrations/2026_11_16_120000_create_knitwear_domain.php', $migration);

$panelMap = [];

foreach ($tables as $table => $meta) {
    $model = $meta['model'];
    $columns = array_map('parseColumn', $meta['columns']);
    $parent = $meta['soft'] ? 'FactoryRecord' : 'AppendOnlyRecord';
    $casts = [];
    $relations = '';
    $uses = ["use Illuminate\\Database\\Eloquent\\Relations\\BelongsTo;\n", "use Illuminate\\Database\\Eloquent\\Relations\\HasMany;\n"];

    foreach ($columns as $col) {
        if ($col['type'] === 'bool') {
            $casts[$col['name']] = 'boolean';
        } elseif ($col['type'] === 'decimal') {
            $casts[$col['name']] = 'decimal:3';
        } elseif ($col['type'] === 'date') {
            $casts[$col['name']] = 'date';
        } elseif ($col['type'] === 'datetime') {
            $casts[$col['name']] = 'datetime';
        } elseif ($col['type'] === 'json') {
            $casts[$col['name']] = 'array';
        } elseif ($col['secret']) {
            $casts[$col['name']] = 'encrypted';
        }
        if ($col['type'] === 'foreign' && $col['ref']) {
            $method = relationMethod($col['name']);
            $related = modelClass($tables, $col['ref']);
            $relatedFq = $related === 'User' ? '\\App\\Models\\User' : $related;
            $relations .= <<<PHP

    public function {$method}(): BelongsTo
    {
        return \$this->belongsTo({$relatedFq}::class, '{$col['name']}');
    }

PHP;
        }
    }

    if (! in_array($table, ['modules', 'settings'], true)) {
        $relations .= <<<'PHP'

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

PHP;
    }

    foreach ($tables as $childTable => $childMeta) {
        foreach ($childMeta['columns'] as $spec) {
            $col = parseColumn($spec);
            if ($col['type'] !== 'foreign' || $col['ref'] !== $table) {
                continue;
            }
            if (in_array($col['name'], ['branch_id', 'created_by', 'updated_by', 'owner_id', 'user_id', 'operator_id', 'approved_by', 'assigned_to'], true)) {
                continue;
            }
            $childModel = $childMeta['model'];
            $method = Str::camel(Str::pluralStudly($childModel));
            $fk = $col['name'];
            $relations .= <<<PHP

    public function {$method}(): HasMany
    {
        return \$this->hasMany({$childModel}::class, '{$fk}');
    }

PHP;
            break;
        }
    }

    $castLines = '';
    foreach ($casts as $name => $cast) {
        $castLines .= '            '.phpString($name).' => '.phpString($cast).",\n";
    }
    $castMethod = $castLines === '' ? '' : <<<PHP

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
{$castLines}        ]);
    }

PHP;

    $modelPhp = <<<PHP
<?php

namespace App\\Models;

use Illuminate\\Database\\Eloquent\\Relations\\BelongsTo;
use Illuminate\\Database\\Eloquent\\Relations\\HasMany;

class {$model} extends {$parent}
{
    protected \$table = '{$table}';
{$castMethod}{$relations}}

PHP;
    file_put_contents(__DIR__."/../app/Models/{$model}.php", $modelPhp);

    $perm = Str::snake(Str::pluralStudly($model));
    $policy = <<<PHP
<?php

namespace App\\Policies;

use App\\Models\\{$model};
use App\\Models\\User;

class {$model}Policy
{
    public function viewAny(User \$user): bool
    {
        return \$user->can('{$perm}.view') || \$user->can('{$perm}.manage');
    }

    public function view(User \$user, {$model} \$record): bool
    {
        return \$this->viewAny(\$user);
    }

    public function create(User \$user): bool
    {
        return \$user->can('{$perm}.manage');
    }

    public function update(User \$user, {$model} \$record): bool
    {
        return \$user->can('{$perm}.manage');
    }

    public function delete(User \$user, {$model} \$record): bool
    {
        return \$user->can('{$perm}.manage');
    }
}

PHP;
    if (! is_dir(__DIR__.'/../app/Policies')) {
        mkdir(__DIR__.'/../app/Policies', 0777, true);
    }
    file_put_contents(__DIR__."/../app/Policies/{$model}Policy.php", $policy);

    $form = [];
    $tableCols = [];
    $search = [];
    foreach ($columns as $col) {
        if (in_array($col['name'], ['created_by', 'updated_by'], true)) {
            continue;
        }
        $fname = $col['name'];
        if ($col['type'] === 'foreign') {
            $method = relationMethod($fname);
            $title = titleColumn($tables, $col['ref']);
            $req = $col['nullable'] ? '' : '->required()';
            $form[] = "Select::make('{$fname}')->relationship('{$method}', '{$title}')->searchable()->preload(){$req}";
        } elseif ($col['type'] === 'text' || $col['type'] === 'json') {
            $req = $col['nullable'] ? '' : '->required()';
            $form[] = "Textarea::make('{$fname}'){$req}->columnSpanFull()";
        } elseif ($col['type'] === 'bool') {
            $form[] = "Toggle::make('{$fname}')";
        } elseif ($col['type'] === 'date') {
            $req = $col['nullable'] ? '' : '->required()';
            $form[] = "DatePicker::make('{$fname}'){$req}";
        } elseif ($col['type'] === 'datetime') {
            $req = $col['nullable'] ? '' : '->required()';
            $form[] = "DateTimePicker::make('{$fname}'){$req}";
        } else {
            $req = $col['nullable'] ? '' : '->required()';
            $numeric = in_array($col['type'], ['int', 'decimal'], true) ? '->numeric()' : '';
            $form[] = "TextInput::make('{$fname}'){$numeric}{$req}";
        }
        if (count($tableCols) < 6 && ! in_array($col['type'], ['text', 'json'], true)) {
            $searchable = in_array($col['type'], ['string'], true) ? '->searchable()' : '';
            $tableCols[] = "TextColumn::make('{$fname}'){$searchable}";
            if (in_array($col['type'], ['string'], true) && in_array($fname, ['name', 'code', 'number', 'title', 'slug', 'buyer_po_no'], true)) {
                $search[] = $fname;
            }
        }
    }
    $form[] = "Hidden::make('lock_version')";
    if (! in_array($table, ['modules', 'settings'], true)) {
        $form[] = "Select::make('branch_id')->relationship('branch', 'name')->searchable()->preload()";
        if (count($tableCols) < 6) {
            $tableCols[] = "TextColumn::make('branch.name')->label('Branch')";
        }
    }

    $formPhp = implode(",\n                ", $form);
    $colsPhp = implode(",\n                ", $tableCols);
    $searchPhp = implode(', ', array_map(fn ($s) => phpString($s), $search));
    $filters = '';
    $names = array_column($columns, 'name');
    if (in_array('status', $names, true)) {
        $filters .= "SelectFilter::make('status'),\n                ";
    }
    if ($meta['soft']) {
        $filters .= "TrashedFilter::make(),\n                ";
    }

    $recordActions = [];
    if (! $meta['readonly']) {
        $recordActions[] = 'EditAction::make()';
        $recordActions[] = 'DeleteAction::make()';
    }
    foreach ($meta['actions'] as $action) {
        $label = Str::headline($action);
        if ($action === 'print') {
            $recordActions[] = "Action::make('print')->label('Print')->url(fn (\$record) => route('documents.print', ['type' => \$record->getTable(), 'id' => \$record->getKey()]))->openUrlInNewTab()";
            continue;
        }
        $recordActions[] = <<<PHP
Action::make('{$action}')->label('{$label}')->requiresConfirmation()->action(function (\$record) {
                    app(\\App\\Services\\Workflow\\DocumentWorkflow::class)->{$action}(\$record);
                    \\Filament\\Notifications\\Notification::make()->title('{$label} completed')->success()->send();
                })
PHP;
    }
    $recordPhp = implode(",\n                ", $recordActions);
    $create = $meta['readonly'] ? 'false' : 'true';
    $moduleKey = $meta['module'];
    $group = $meta['group'];

    $resource = <<<PHP
<?php

namespace App\\Filament\\Resources;

use App\\Filament\\Resources\\{$model}\\Pages\\Manage{$model};
use App\\Models\\{$model};
use App\\Support\\Modules\\ModuleRegistry;
use Filament\\Actions\\Action;
use Filament\\Actions\\DeleteAction;
use Filament\\Actions\\EditAction;
use Filament\\Forms\\Components\\DatePicker;
use Filament\\Forms\\Components\\DateTimePicker;
use Filament\\Forms\\Components\\Hidden;
use Filament\\Forms\\Components\\Select;
use Filament\\Forms\\Components\\Textarea;
use Filament\\Forms\\Components\\TextInput;
use Filament\\Forms\\Components\\Toggle;
use Filament\\Resources\\Resource;
use Filament\\Schemas\\Schema;
use Filament\\Tables\\Columns\\TextColumn;
use Filament\\Tables\\Filters\\SelectFilter;
use Filament\\Tables\\Filters\\TrashedFilter;
use Filament\\Tables\\Table;
use Symfony\\Component\\HttpFoundation\\StreamedResponse;

class {$model}Resource extends Resource
{
    protected static ?string \$model = {$model}::class;

    protected static string|\\UnitEnum|null \$navigationGroup = '{$group}';

    protected static ?string \$moduleKey = '{$moduleKey}';

    public static function canViewAny(): bool
    {
        if (static::\$moduleKey && ! app(ModuleRegistry::class)->enabled(static::\$moduleKey)) {
            return false;
        }

        return parent::canViewAny();
    }

    public static function canCreate(): bool
    {
        return {$create} && parent::canCreate();
    }

    public static function form(Schema \$schema): Schema
    {
        return \$schema->components([
                {$formPhp},
            ]);
    }

    public static function table(Table \$table): Table
    {
        return \$table
            ->columns([
                {$colsPhp},
            ])
            ->filters([
                {$filters}
            ])
            ->recordActions([
                {$recordPhp}
            ])
            ->headerActions([
                Action::make('exportCsv')->label('Export CSV')->action(function (): StreamedResponse {
                    \$filename = '{$table}.csv';

                    return response()->streamDownload(function (): void {
                        \$handle = fopen('php://output', 'w');
                        \$first = {$model}::query()->first();
                        if (! \$first) {
                            fclose(\$handle);

                            return;
                        }
                        fputcsv(\$handle, array_keys(\$first->getAttributes()));
                        {$model}::query()->orderByDesc('id')->limit(5000)->chunk(200, function (\$rows) use (\$handle): void {
                            foreach (\$rows as \$row) {
                                fputcsv(\$handle, \$row->getAttributes());
                            }
                        });
                        fclose(\$handle);
                    }, \$filename);
                }),
            ])
            ->defaultSort('id', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Manage{$model}::route('/'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [{$searchPhp}];
    }
}

PHP;

    file_put_contents(__DIR__."/../app/Filament/Resources/{$model}Resource.php", $resource);
    $pageDir = __DIR__."/../app/Filament/Resources/{$model}/Pages";
    if (! is_dir($pageDir)) {
        mkdir($pageDir, 0777, true);
    }
    $header = $meta['readonly']
        ? 'return [];'
        : "return [\\Filament\\Actions\\CreateAction::make()];";
    $page = <<<PHP
<?php

namespace App\\Filament\\Resources\\{$model}\\Pages;

use App\\Filament\\Resources\\{$model}Resource;
use Filament\\Resources\\Pages\\ManageRecords;

class Manage{$model} extends ManageRecords
{
    protected static string \$resource = {$model}Resource::class;

    protected function getHeaderActions(): array
    {
        {$header}
    }
}

PHP;
    file_put_contents($pageDir."/Manage{$model}.php", $page);
    $panelMap[$meta['panel']][] = "\\App\\Filament\\Resources\\{$model}Resource::class";
}

$mapPhp = "<?php\n\nnamespace App\\Filament;\n\nclass PanelMap\n{\n    /** @return list<class-string> */\n    public static function resources(string \$panel): array\n    {\n        return match (\$panel) {\n";
foreach ($panelMap as $panel => $classes) {
    $mapPhp .= "            '{$panel}' => [\n                ".implode(",\n                ", $classes).",\n            ],\n";
}
$mapPhp .= "            default => [],\n        };\n    }\n}\n";
file_put_contents(__DIR__.'/../app/Filament/PanelMap.php', $mapPhp);

echo 'Generated '.count($tables)." tables\n";
