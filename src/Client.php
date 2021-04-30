<?php

declare(strict_types=1);

namespace Enlight\PingPing;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\PendingRequest;
use Enlight\PingPing\Exceptions\UrlRequiredException;
use Enlight\PingPing\Exceptions\AliasRequiredException;
use Enlight\PingPing\Exceptions\MonitorIDRequiredException;

class Client
{
    use HasFake;

    /** @var string */
    protected $uri;

    /** @var string */
    protected $token;

    /** @var int */
    protected $timeout;

    /** @var int|null */
    protected $retryTimes;

    /** @var int|null */
    protected $retryMilliseconds;

    public function __construct(
        string $uri,
        string $token,
        int $timeout = 10,
        $retryTimes = null,
        $retryMilliseconds = null
    ) {
        $this->uri = $uri;
        $this->token = $token;
        $this->timeout = $timeout;
        $this->retryTimes = $retryTimes;
        $this->retryMilliseconds = $retryMilliseconds;
    }

    /**
     * Retrieve all websites.
     *
     * @param int $id
     * @return Response
     */
    public function monitors(int $id = 0): Response
    {
        $request = $this->createRequest();

        $path = $id ? "$this->uri/monitors/$id" : "$this->uri/monitors";

        return $request->get($path);
    }

    /**
     * Retrieve statistics from a specific website.
     *
     * @param int $id
     * @return Response
     * @throws MonitorIDRequiredException
     */
    public function statistics(int $id = 0): Response
    {
        if ($id === 0) {
            throw new MonitorIDRequiredException();
        }

        $request = $this->createRequest();

        $path = "$this->uri/monitors/$id/statistics";

        return $request->get($path);
    }

    /**
     * Create a website.
     *
     * @param string $url
     * @return Response
     * @throws UrlRequiredException
     */
    public function createMonitor(string $url = ''): Response
    {
        if ($url === '') {
            throw new UrlRequiredException();
        }

        // @todo validate if $url is valid.

        $request = $this->createRequest();

        return $request->post("$this->uri/monitors", [
            'url' => $url,
        ]);
    }

    /**
     * Update a website.
     *
     * @param int $id
     * @param string $url
     * @param string $alias
     * @return Response
     * @throws MonitorIDRequiredException|UrlRequiredException|AliasRequiredException
     */
    public function updateMonitor(int $id = 0, string $url = '', string $alias = ''): Response
    {
        if ($id === 0) {
            throw new MonitorIDRequiredException();
        }

        if ($url === '') {
            throw new UrlRequiredException();
        }

        if ($alias === '') {
            throw new AliasRequiredException();
        }

        $request = $this->createRequest();

        return $request->put("$this->uri/monitors/$id", [
            'url' => $url,
            'alias' => $alias,
        ]);
    }

    /**
     * Delete a specific website.
     *
     * @param int $id
     * @return Response
     * @throws MonitorIDRequiredException
     */
    public function deleteMonitor(int $id = 0): Response
    {
        if ($id === 0) {
            throw new MonitorIDRequiredException();
        }

        $request = $this->createRequest();

        return $request->delete("$this->uri/monitors/$id");
    }

    /**
     * Build Http Request.
     *
     * @return PendingRequest
     */
    private function createRequest(): PendingRequest
    {
        $request = Http::withToken($this->token)
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->timeout($this->timeout);

        if (! is_null($this->retryTimes) && ! is_null($this->retryMilliseconds)) {
            $request->retry($this->retryTimes, $this->retryMilliseconds);
        }

        return $request;
    }
}
