<?php

declare(strict_types=1);

namespace App\Rate\Exception;

use Exception;
use Throwable;

final class GetExchangeRatesRequestException extends Exception
{
    public function __construct($message = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
