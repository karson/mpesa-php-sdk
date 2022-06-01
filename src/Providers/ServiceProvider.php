<?php

namespace Karson\MpesaPhpSdk\Providers;

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
            $mpesa =  new \Karson\MpesaPhpSdk\Mpesa();

            $mpesa->setPublicKey(config('mpesa.public_key'));
            $mpesa->setApiKey(config('mpesa.api_key'));//test
            $mpesa->setEnv(config('mpesa.env'));
            $mpesa->setServiceProviderCode(config('mpesa.service_provider_code'));

            return $mpesa;
        });
    }
}
