<?php

declare(strict_types=1);

namespace Tests\Unit;

use Tests\TestCase;
use Enlight\PingPing\Client;
use Enlight\PingPing\Exceptions\TokenRequiredException;

class ClientTest extends TestCase
{
    /** @test */
    public function api_token_is_required()
    {
        config(['ping-ping.token' => null]);

        $this->expectException(TokenRequiredException::class);

        app(Client::class);
    }
}
