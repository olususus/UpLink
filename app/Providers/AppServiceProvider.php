<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Console\Commands\SmartSchedulerCommand;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                SmartSchedulerCommand::class,
            ]);
        }

        // Explicitly register 'role' middleware alias if not already registered
        $router = $this->app['router'];
        $router->aliasMiddleware('role', \App\Http\Middleware\RoleMiddleware::class);
    }
}
