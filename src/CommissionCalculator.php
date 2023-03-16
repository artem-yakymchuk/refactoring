<?php

declare(strict_types=1);

namespace App;

use App\Bin\BinLookupProviderInterface;
use App\Bin\GetCountryComissionMultiplierInterface;
use App\Entity\Operation;
use App\Entity\OperationWithCommission;
use App\Rate\ExchangeRatesProviderInterface;

final class CommissionCalculator
{
    private BinLookupProviderInterface $binLookupProvider;
    private GetCountryComissionMultiplierInterface $getCountryCommissionMultiplier;
    private ExchangeRatesProviderInterface $exchangeRatesProvider;

    public function __construct(
        BinLookupProviderInterface $binLookupProvider,
        GetCountryComissionMultiplierInterface $getCountryCommissionMultiplier,
        ExchangeRatesProviderInterface $exchangeRatesProvider
    ) {
        $this->binLookupProvider = $binLookupProvider;
        $this->getCountryCommissionMultiplier = $getCountryCommissionMultiplier;
        $this->exchangeRatesProvider = $exchangeRatesProvider;
    }

    /**
     * @param Operation ...$operations
     * @return array
     * @throws Bin\Exception\BinLookupException
     * @throws Rate\Exception\GetExchangeRatesRequestException
     * @throws Rate\Exception\MissingExchangeRateException
     */
    public function calculate(Operation ...$operations): array
    {
        $exchangeRates = $this->exchangeRatesProvider->getExchangeRates();

        $result = [];
        foreach ($operations as $operation) {
            $binLookup = $this->binLookupProvider->lookupBin($operation->getBin());
            $exchangeRate = $exchangeRates->getRate($operation->getCurrency());

            if ($operation->getCurrency() === 'EUR' || $exchangeRate->getRate() === 1.0) {
                $result[] = OperationWithCommission::create(
                    $operation,
                    $operation->getAmount() * $this->getCountryCommissionMultiplier->execute(
                        $binLookup->getCountry()
                    )
                );
                continue;
            }

            $result[] = OperationWithCommission::create(
                $operation,
                ($operation->getAmount() / $exchangeRate->getRate()) * $this->getCountryCommissionMultiplier->execute(
                    $binLookup->getCountry()
                )
            );
        }

        return $result;
    }
}
