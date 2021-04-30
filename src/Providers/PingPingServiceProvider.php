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
        $this->mergeConfigFrom(__DIR__ . '/../../config/ping-ping.php', 'ping-ping');

        $this->app->singleton(Client::class, function () {
            return new Client(
                config('ping-ping.uri'),
                config('ping-ping.token'),
                config('ping-ping.timeout'),
                config('ping-ping.retry_times'),
                config('ping-ping.retry_milliseconds')
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
                __DIR__ . '/../../config/ping-ping.php' => config_path('ping-ping.php'),
            ], 'config');
        }
    }
}
