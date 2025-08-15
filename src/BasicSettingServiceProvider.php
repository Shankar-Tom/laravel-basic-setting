<?php

namespace Shankar\LaravelBasicSetting;

use Illuminate\Support\ServiceProvider;
use Shankar\LaravelBasicSetting\Services\GlobalSearch;

class BasicSettingServiceProvider extends ServiceProvider
{
    public function boot()
    {

        $this->loadRoutesFrom(__DIR__ . '/Routes/web.php');
        $this->app['router']->pushMiddlewareToGroup('web', \Shankar\LaravelBasicSetting\Middleware\InformMe::class);
    }

    public function register() {}
}
