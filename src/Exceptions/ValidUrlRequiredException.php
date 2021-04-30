<?php

declare(strict_types=1);

namespace Enlight\PingPing\Exceptions;

use Exception;
use Throwable;

class ValidUrlRequiredException extends Exception
{
    public function __construct(
        $message = 'Valid Domain URL is required.',
        $code = 0,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
