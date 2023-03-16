<?php

namespace App\Rate\Http;

use App\Rate\Exception\GetExchangeRatesRequestException;
use Symfony\Contracts\HttpClient\ResponseInterface;

interface ExchangeRateClientInterface
{
    /**
     * @return ResponseInterface
     * @throws GetExchangeRatesRequestException
     */
    public function request(): ResponseInterface;
}
