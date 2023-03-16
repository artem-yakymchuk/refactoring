<?php

declare(strict_types=1);

namespace App\Bin\Http;

use App\Bin\Exception\BinLookupException;
use Symfony\Contracts\HttpClient\ResponseInterface;

interface BinListClientInterface
{
    /**
     * @param string $bin
     * @return ResponseInterface
     * @throws BinLookupException
     */
    public function request(string $bin): ResponseInterface;
}
