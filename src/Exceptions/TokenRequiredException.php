<?php

declare(strict_types=1);

namespace Enlight\PingPing\Exceptions;

use Exception;
use Throwable;

class TokenRequiredException extends Exception
{
    public function __construct(
        $message = 'Please add PING_PING_API_TOKEN in your .env file with valid token.',
        $code = 0,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
