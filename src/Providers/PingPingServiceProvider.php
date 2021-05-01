<?php

declare(strict_types=1);

namespace Enlight\PingPing\Providers;

use Enlight\PingPing\Client;
use Illuminate\Support\ServiceProvider;

class PingPingServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/pingping.php', 'pingping');

        $this->app->singleton(Client::class, function () {
            return new Client(
                config('pingping.uri'),
                config('pingping.token'),
                config('pingping.timeout'),
                config('pingping.retry_times'),
                config('pingping.retry_milliseconds')
            );
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../config/pingping.php' => config_path('pingping.php'),
            ], 'config');
        }
    }
}
