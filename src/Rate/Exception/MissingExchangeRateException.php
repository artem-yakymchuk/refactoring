<?php

declare(strict_types=1);

namespace App\Rate\Exception;

use Exception;
use Throwable;

final class MissingExchangeRateException extends Exception
{
    public function __construct(string $rateSymbol, $code = 0, Throwable $previous = null)
    {
        parent::__construct(sprintf('Missing requested exchange rate for %s', $rateSymbol), $code, $previous);
    }
}
