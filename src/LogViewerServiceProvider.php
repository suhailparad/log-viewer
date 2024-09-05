<?php

namespace Suhailparad\LogViewer;

use Illuminate\Support\ServiceProvider;

class LogViewerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        // Load routes
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        // Load views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'log-viewer');

        $this->publishes([
            __DIR__.'/../config/log-viewer.php' => config_path('log-viewer.php'),
        ]);

    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/log-viewer.php', 'log-viewer');
    }
}
