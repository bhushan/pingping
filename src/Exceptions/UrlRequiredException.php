<?php

declare(strict_types=1);

namespace Enlight\PingPing\Exceptions;

use Exception;
use Throwable;

class UrlRequiredException extends Exception
{
    public function __construct(
        $message = 'Valid Domain URL is required to update monitor.',
        $code = 0,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
