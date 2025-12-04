<?php

namespace Karson\MpesaPhpSdk\Laravel;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Karson\MpesaPhpSdk\Mpesa;

/**
 * Service provider
 */
class ServiceProvider extends BaseServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/mpesa.php'=> config_path('mpesa.php'),
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/mpesa.php',
            'mpesa'
        );

        $this->app->bind(Mpesa::class, function ($app) {
            $mpesa =  new Mpesa(
                $app['config']['mpesa.public_key'],
                $app['config']['mpesa.api_key'],
                $app['config']['mpesa.is_test'],
                $app['config']['mpesa.service_provider_code']
            );
            return $mpesa;
        });
    }
}
