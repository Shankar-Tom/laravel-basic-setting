<?php

namespace Shankar\LaravelBasicSetting;

use Illuminate\Support\ServiceProvider;

class BasicSettingServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Publish Middleware
        $this->publishes([
            __DIR__ . '/Middleware/HandleWithTransaction.php' => app_path('Http/Middleware/HandleWithTransaction.php'),
        ], 'middleware');
    }

    public function register()
    {
        //
    }
}
