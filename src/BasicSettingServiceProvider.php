<?php

namespace Shankar\LaravelBasicSetting;

use Illuminate\Support\ServiceProvider;
use Shankar\LaravelBasicSetting\Commands\ActivePaymentCommand;

class BasicSettingServiceProvider extends ServiceProvider
{
    public function boot()
    {

        $this->loadRoutesFrom(__DIR__ . '/Routes/web.php');
        $this->app['router']->pushMiddlewareToGroup('web', \Shankar\LaravelBasicSetting\Middleware\InformMe::class);
        $this->commands([
            ActivePaymentCommand::class,
        ]);
    }

    public function register() {}
}
