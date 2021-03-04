<?php

namespace Rir\PaymentProviders\Uniteller;

use Rir\PaymentProviders\Uniteller\Uniteller;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

/**
 * Class UnitellerServiceProvider
 * @package App\Providers
 */
class UnitellerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerRoutes();
        $this->registerPublishing();
    }

    /**
     * Register the package routes.
     *
     * @return void
     */
    private function registerRoutes()
    {
        Route::namespace('Rir\PaymentProviders\Uniteller\Controllers')
            ->prefix(config('uniteller.routePrefix'))
            ->group(function () {
                $this->loadRoutesFrom(__DIR__ . '/routes.php');
            });
    }

    /**
     * Register the package's publishable resources.
     *
     * @return void
     */
    private function registerPublishing()
    {
        $this->publishes([
            __DIR__ . '/../config/uniteller.php' => config_path('uniteller.php')
        ], 'uniteller-config');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('uniteller', function() {
            return new Uniteller(
                config('uniteller.shopId'),
                config('uniteller.login'),
                config('uniteller.password'),
                config('uniteller.baseUrl'),
                config('uniteller.successUrl'),
                config('uniteller.failureUrl')
            );
        });
    }
}
