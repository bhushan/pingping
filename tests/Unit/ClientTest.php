<?php

declare(strict_types=1);

namespace Tests\Unit;

use Tests\TestCase;
use Enlight\PingPing\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;
use Symfony\Component\HttpFoundation\Response;
use Enlight\PingPing\Exceptions\AliasRequiredException;
use Enlight\PingPing\Exceptions\TokenRequiredException;
use Enlight\PingPing\Exceptions\ValidUrlRequiredException;
use Enlight\PingPing\Exceptions\MonitorIDRequiredException;

class ClientTest extends TestCase
{
    private $validUrl = 'https://my-cool-website.test';

    protected function setUp(): void
    {
        parent::setUp();

        config(['pingping.token' => 'fake-token']);
    }

    /** @test */
    public function api_token_is_required()
    {
        config(['pingping.token' => null]);

        $this->expectException(TokenRequiredException::class);

        app(Client::class);
    }

    /** @test */
    public function request_could_be_retried_multiple_times()
    {
        Client::fake(function () {
            return Http::response('Hello World', 500);
        });

        $retryTimes = 2;

        config([
            'pingping.retry_times' => $retryTimes,
            'pingping.retry_milliseconds' => 10,
        ]);

        $this->expectException(RequestException::class);

        app(Client::class)->monitors()->assertSentCount($retryTimes);
    }

    /** @test */
    public function user_can_get_information_of_all_monitors()
    {
        Client::fake();

        $request = app(Client::class)->monitors();

        $this->assertSame($request->status(), Response::HTTP_OK);
    }

    /** @test */
    public function user_can_get_specific_monitor_information()
    {
        Client::fake();

        $request = app(Client::class)->monitors($random = 999);

        $this->assertSame($request->status(), Response::HTTP_OK);
    }

    /** @test */
    public function statistics_requires_monitor_id()
    {
        $this->expectException(MonitorIDRequiredException::class);

        app(Client::class)->statistics();
    }

    /** @test */
    public function with_valid_monitor_id_user_can_see_statistics()
    {
        Client::fake();

        $request = app(Client::class)->statistics($random = 999);

        $this->assertSame($request->status(), Response::HTTP_OK);
    }

    /** @test */
    public function to_create_monitor_url_is_required()
    {
        $this->expectException(ValidUrlRequiredException::class);

        app(Client::class)->createMonitor();
    }

    /** @test */
    public function to_create_monitor_valid_url_is_required()
    {
        $this->expectException(ValidUrlRequiredException::class);

        app(Client::class)->createMonitor('not-proper-url');
    }

    /** @test */
    public function with_valid_url_user_can_create_monitor()
    {
        Client::fake();

        $request = app(Client::class)->createMonitor($this->validUrl);

        $this->assertSame($request->status(), Response::HTTP_OK);
    }

    /** @test */
    public function to_update_monitor_id_is_required()
    {
        $this->expectException(MonitorIDRequiredException::class);

        app(Client::class)->updateMonitor();
    }

    /** @test */
    public function to_update_monitor_url_is_required()
    {
        $this->expectException(ValidUrlRequiredException::class);

        app(Client::class)->updateMonitor($randomId = 999);
    }

    /** @test */
    public function to_update_monitor_valid_url_is_required()
    {
        $this->expectException(ValidUrlRequiredException::class);

        app(Client::class)->updateMonitor($randomId = 999, 'not-proper-url');
    }

    /** @test */
    public function to_update_monitor_alias_is_required()
    {
        $this->expectException(AliasRequiredException::class);

        app(Client::class)->updateMonitor($randomId = 999, $this->validUrl);
    }

    /** @test */
    public function with_valid_data_user_can_update_monitor()
    {
        Client::fake();

        $request = app(Client::class)->updateMonitor($randomId = 999, $this->validUrl, 'some-alias');

        $this->assertSame($request->status(), Response::HTTP_OK);
    }

    /** @test */
    public function to_delete_monitor_id_is_required()
    {
        $this->expectException(MonitorIDRequiredException::class);

        app(Client::class)->deleteMonitor();
    }

    /** @test */
    public function with_valid_monitor_id_user_can_delete_monitor()
    {
        Client::fake();

        $request = app(Client::class)->deleteMonitor($randomId = 999);

        $this->assertSame($request->status(), Response::HTTP_OK);
    }
}
