<?php

namespace Karson\MpesaPhpSdk\Providers;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

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

        $this->app->bind('\Karson\MpesaPhpSdk\Mpesa', function ($app) {
            $mpesa =  new \Karson\MpesaPhpSdk\Mpesa();

            $mpesa->setPublicKey(conig('mpesa.public_key'));
            $mpesa->setApiKey(conig('mpesa.api_key'));//test
            $mpesa->setEnv(conig('mpesa.env'));
            $mpesa->setServiceProviderCode(conig('mpesa.service_provider_code'));

            return $mpesa;
        });
    }
}
