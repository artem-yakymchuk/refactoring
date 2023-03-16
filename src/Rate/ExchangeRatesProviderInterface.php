<?php

namespace App\Rate;

use App\Rate\Entity\ExchangeRates;
use App\Rate\Exception\GetExchangeRatesRequestException;

interface ExchangeRatesProviderInterface
{
    /**
     * @return ExchangeRates
     * @throws GetExchangeRatesRequestException
     */
    public function getExchangeRates(): ExchangeRates;
}
