<?php

namespace App\Support\Modules;

use App\Models\Module;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class ModuleRegistry
{
    public function enabled(string $key): bool
    {
        if (! Schema::hasTable('modules')) {
            return true;
        }

        $map = Cache::remember('factory.modules', 60, function (): array {
            return Module::query()->pluck('enabled', 'key')->all();
        });

        if (! array_key_exists($key, $map)) {
            return true;
        }

        return (bool) $map[$key];
    }

    public function forget(): void
    {
        Cache::forget('factory.modules');
    }
}
