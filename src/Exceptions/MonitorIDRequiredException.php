<?php

declare(strict_types=1);

namespace Enlight\PingPing\Exceptions;

use Exception;
use Throwable;

class MonitorIDRequiredException extends Exception
{
    public function __construct(
        $message = 'Monitor ID is required.',
        $code = 0,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
