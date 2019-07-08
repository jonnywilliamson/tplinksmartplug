<?php

namespace Williamson\TPLinkSmartplug\Laravel;

use Illuminate\Support\ServiceProvider;
use Williamson\TPLinkSmartplug\TPLinkManager;

class TPLinkServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([__DIR__ . '/config/TPLink.php' => config_path('TPLink.php')], 'config');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(TPLinkManager::class, function () {
            return new TPLinkManager(config('TPLink'));
        });

        $this->app->alias(TPLinkManager::class, 'tplink');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [TPLinkManager::class];
    }
}
