<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class CustomRouteServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Use this method to register array-style routes
        Route::macro('arrayAction', function ($uri, $action) {
            if (is_array($action)) {
                return Route::get($uri, function () use ($action) {
                    return app($action[0])->{$action[1]}();
                });
            }

            return Route::get($uri, $action);
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
