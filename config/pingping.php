<?php

declare(strict_types=1);

return [
    'uri' => env('PING_PING_API_URL', 'https://pingping.io/webapi'),
    'token' => env('PING_PING_API_TOKEN'),
    'timeout' => env('PING_PING_TIMEOUT', 10),
    'retry_times' => env('PING_PING_RETRY_TIMES'),
    'retry_milliseconds' => env('PING_PING_RETRY_MILLISECONDS'),
];
