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
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');

        // Load views
        $this->loadViewsFrom(__DIR__.'/resources/views', 'log-viewer');
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Register any bindings or singletons
    }
}
