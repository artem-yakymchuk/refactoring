<?php

declare(strict_types=1);

namespace App\Bin\Exception;

use Exception;
use Throwable;

final class BinLookupException extends Exception
{
    public function __construct($message = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
