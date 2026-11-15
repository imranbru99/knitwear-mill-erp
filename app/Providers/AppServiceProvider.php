<?php

namespace App\Providers;

use App\Support\Modules\ModuleRegistry;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Password::defaults(fn () => Password::min(8)->mixedCase()->numbers());

        RateLimiter::for('api', fn (Request $request) => Limit::perMinute(60)->by($request->user()?->id ?: $request->ip()));
        RateLimiter::for('inquiry', fn (Request $request) => Limit::perMinute(5)->by($request->ip()));

        Gate::before(function ($user, string $ability) {
            if ($ability === 'webshell.access') {
                return $user->sees_all_branches && app(ModuleRegistry::class)->enabled('webshell');
            }
            if ($user->sees_all_branches || (method_exists($user, 'hasRole') && $user->hasRole('Super Admin'))) {
                return true;
            }

            return null;
        });
    }
}
