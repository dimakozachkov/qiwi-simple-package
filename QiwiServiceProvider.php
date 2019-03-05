<?php

namespace Dimakozachkov\QiwiSimplePackage;

use Illuminate\Support\ServiceProvider;
use Dimakozachkov\QiwiSimplePackage\Helpers\QiwiHelper;
use Dimakozachkov\QiwiSimplePackage\Helpers\QiwiHelperInterface;

class QiwiServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $path = __DIR__ . '/config/qiwi.php';

        $this->publishes([
            $path => config_path('qiwi.php')
        ], 'config');

        $this->mergeConfigFrom($path, 'qiwi');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(QiwiHelperInterface::class, QiwiHelper::class);
    }
}
