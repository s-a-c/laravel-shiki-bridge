<?php

declare(strict_types=1);

namespace Sac\ShikiBridge\Providers;

use Illuminate\Support\ServiceProvider;
use Override;
use Sac\ShikiBridge\Commands\GenerateShikiCss;
use Sac\ShikiBridge\Components\ShikiCode;

final class ShikiBridgeServiceProvider extends ServiceProvider
{
    #[Override]
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/shiki-bridge.php', 'shiki-bridge');
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                GenerateShikiCss::class,
            ]);

            $this->publishes([
                __DIR__.'/../../config/shiki-bridge.php' => config_path('shiki-bridge.php'),
            ], 'shiki-bridge-config');

            $this->publishes([
                __DIR__.'/../../resources/css/shiki-bridge.css' => public_path('css/shiki-bridge.css'),
            ], 'shiki-bridge-assets');
        }

        // Register the Blade Component
        // Using strict alias for cleaner usage <x-shiki-code />
        $this->loadViewComponentsAs('shiki', [
            'code' => ShikiCode::class,
        ]);
    }
}
