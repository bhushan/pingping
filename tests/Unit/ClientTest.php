<?php

declare(strict_types=1);

namespace Tests\Unit;

use TypeError;
use Tests\TestCase;
use Enlight\PingPing\Client;

class ClientTest extends TestCase
{
    /** @test */
    public function api_token_is_required()
    {
        config(['ping-ping.token' => null]);

        $this->expectException(TypeError::class);

        app(Client::class);
    }
}
