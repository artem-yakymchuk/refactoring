<?php

declare(strict_types=1);

namespace App\Rate;

use App\Rate\Entity\ExchangeRate;
use App\Rate\Entity\ExchangeRates;
use App\Rate\Exception\GetExchangeRatesRequestException;
use App\Rate\Http\ExchangeRateClientInterface;

final class ExchangeRatesProvider implements ExchangeRatesProviderInterface
{
    private ExchangeRateClientInterface $exchangeRateClient;

    public function __construct(ExchangeRateClientInterface $exchangeRateClient)
    {
        $this->exchangeRateClient = $exchangeRateClient;
    }

    /**
     * @return ExchangeRates
     * @throws GetExchangeRatesRequestException
     */
    public function getExchangeRates(): ExchangeRates
    {
        $response = $this->exchangeRateClient->request();
        $exchangeRates = [];
        //Todo change json_decode to serializer
        foreach ((json_decode($response->getContent(), true)['rates'] ?? []) as $symbol => $rate) {
            $exchangeRates[] = ExchangeRate::create($symbol, $rate);
        }

        return new ExchangeRates(...$exchangeRates);
    }
}
