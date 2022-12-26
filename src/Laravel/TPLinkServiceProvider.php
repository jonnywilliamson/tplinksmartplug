<?php

namespace Williamson\TPLinkSmartplug\Laravel;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Williamson\TPLinkSmartplug\TPLinkManager;
use Williamson\TPLinkSmartplug\Laravel\Facades\TPLinkFacade;

class TPLinkServiceProvider extends ServiceProvider
{

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

        // Auto-register the TPLink facade if the user hasn't already
        // assigned it to another class. Takes care of Laravel <5.5 users.
        if (class_exists(AliasLoader::class)) {
            $loader = AliasLoader::getInstance();

            if (!array_key_exists('TPLink', $loader->getAliases())) {
                $loader->alias('TPLink', TPLinkFacade::class);
            }
        }
    }
}
