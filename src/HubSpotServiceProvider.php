<?php

namespace HubSpot\ObjectCollection;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class HubSpotServiceProvider extends ServiceProvider
{
    protected $defer = false;

    public function boot()
    {
        if (!function_exists('config_path')) {
            function config_path($path = '')
            {
                return app()->basePath() . '/config' . ($path ? '/' . $path : $path);
            }
        }

        //Merge config first, then keep a publish option
        $this->mergeConfigFrom(__DIR__.'/config/hubspot.php', 'monday');
        $this->publishes([
            __DIR__.'/config/hubspot.php' => config_path('hubspot.php'),
        ], 'config');

        $router = $this->app->make(Router::class);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

    }
}
