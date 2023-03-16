<?php

declare(strict_types=1);

namespace App\Rate\Entity;

use App\Rate\Exception\MissingExchangeRateException;

final class ExchangeRates
{
    private array $exchangeRates;

    public function __construct(ExchangeRate ...$exchangeRates)
    {
        foreach ($exchangeRates as $exchangeRate) {
            $this->exchangeRates[$exchangeRate->getSymbol()] = $exchangeRate;
        }
    }

    /**
     * @param string $symbol
     * @return ExchangeRate
     * @throws MissingExchangeRateException
     */
    public function getRate(string $symbol): ExchangeRate
    {
        if (!($this->exchangeRates[$symbol] ?? false)) {
            throw new MissingExchangeRateException($symbol);
        }

        return $this->exchangeRates[$symbol];
    }
}
