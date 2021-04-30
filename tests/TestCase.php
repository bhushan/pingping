<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Application;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Enlight\PingPing\Providers\PingPingServiceProvider;

abstract class TestCase extends OrchestraTestCase
{
    /**
     * Load migrations from directory.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutExceptionHandling();
    }

    /**
     * Get package providers.
     *
     * @param Application $app
     * @return array
     */
    protected function getPackageProviders($app): array
    {
        return [
            PingPingServiceProvider::class,
        ];
    }
}
