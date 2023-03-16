<?php

declare(strict_types=1);

namespace Tests;

use App\Bin\BinLookupProviderInterface;
use App\Bin\Entity\BinLookup;
use App\Bin\Entity\Country;
use App\Bin\GetCountryComissionMultiplierInterface;
use App\CommissionCalculator;
use App\Entity\Operation;
use App\Rate\Entity\ExchangeRate;
use App\Rate\Entity\ExchangeRates;
use App\Rate\ExchangeRatesProviderInterface;
use PHPUnit\Framework\TestCase;

class CommissionCalculatorTest extends TestCase
{
    private $commissionCalculator;
    private $binLookupProvider;
    private $getCountryCommissionMultiplier;
    private $exchangeRatesProvider;

    protected function setUp(): void
    {
        $this->binLookupProvider = $this->createMock(BinLookupProviderInterface::class);
        $this->getCountryCommissionMultiplier = $this->createMock(GetCountryComissionMultiplierInterface::class);
        $this->exchangeRatesProvider = $this->createMock(ExchangeRatesProviderInterface::class);

        $this->commissionCalculator = new CommissionCalculator(
            $this->binLookupProvider,
            $this->getCountryCommissionMultiplier,
            $this->exchangeRatesProvider
        );
    }

    public function testCalculate(): void
    {
        $operation1 = new Operation('123456', 1000.0, 'EUR');
        $operation2 = new Operation('234567', 2000.0, 'USD');

        $binLookup1 = $this->createMock(BinLookup::class);
        $country1 = $this->createMock(Country::class);
        $country1->method('getAlpha2')->willReturn('DE');
        $binLookup1->method('getCountry')->willReturn($country1);
        $this->binLookupProvider->method('lookupBin')->willReturn($binLookup1);

        $binLookup2 = $this->createMock(BinLookup::class);
        $country2 = $this->createMock(Country::class);
        $country2->method('getAlpha2')->willReturn('US');
        $binLookup2->method('getCountry')->willReturn($country2);
        $this->binLookupProvider->method('lookupBin')->willReturn($binLookup2);

        $eurExchangeRate = new ExchangeRate('EUR', 1.0);
        $usdExchangeRate = new ExchangeRate('USD', 1.2);

        $this->exchangeRatesProvider->method('getExchangeRates')
            ->willReturn(new ExchangeRates($eurExchangeRate, $usdExchangeRate));

        $this->getCountryCommissionMultiplier->method('execute')->willReturn(0.01);

        $result = $this->commissionCalculator->calculate($operation1, $operation2);

        $this->assertCount(2, $result);

        $expectedCommission1 = 10; // 1000 * 0.01
        $expectedCommission2 = 16.67; // (2000 / 1.2) * 0.01

        $this->assertEquals($operation1, $result[0]->getOperation());
        $this->assertEquals($expectedCommission1, $result[0]->getCommission());

        $this->assertEquals($operation2, $result[1]->getOperation());
        $this->assertEquals($expectedCommission2, $result[1]->getCommission(), '', 0.01);
    }
}
