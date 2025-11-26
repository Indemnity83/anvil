<?php

namespace Anvil;

use Anvil\Console\BuildAnvilPackageCommand;
use Illuminate\Support\ServiceProvider;

class AnvilServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Merge default config
        $this->mergeConfigFrom(__DIR__.'/../config/anvil.php', 'anvil');
    }

    public function boot(): void
    {
        // Publish config for the host app
        $this->publishes([
            __DIR__.'/../config/anvil.php' => config_path('anvil.php'),
        ], 'anvil-config');

        // Register artisan commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                BuildAnvilPackageCommand::class,
            ]);
        }
    }
}
