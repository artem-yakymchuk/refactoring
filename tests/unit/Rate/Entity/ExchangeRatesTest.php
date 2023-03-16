<?php

declare(strict_types=1);

namespace App\Tests\Rate\Entity;

use App\Rate\Entity\ExchangeRate;
use App\Rate\Entity\ExchangeRates;
use App\Rate\Exception\MissingExchangeRateException;
use PHPUnit\Framework\TestCase;

class ExchangeRatesTest extends TestCase
{
    public function testGetRateReturnsCorrectExchangeRate(): void
    {
        $usdRate = new ExchangeRate('USD', 1.0);
        $eurRate = new ExchangeRate('EUR', 0.8);
        $gbpRate = new ExchangeRate('GBP', 0.7);

        $exchangeRates = new ExchangeRates($usdRate, $eurRate, $gbpRate);

        $this->assertSame($usdRate, $exchangeRates->getRate('USD'));
        $this->assertSame($eurRate, $exchangeRates->getRate('EUR'));
        $this->assertSame($gbpRate, $exchangeRates->getRate('GBP'));
    }

    public function testGetRateThrowsExceptionForMissingExchangeRate(): void
    {
        $usdRate = new ExchangeRate('USD', 1.0);
        $eurRate = new ExchangeRate('EUR', 0.8);
        $gbpRate = new ExchangeRate('GBP', 0.7);

        $exchangeRates = new ExchangeRates($usdRate, $eurRate, $gbpRate);

        $this->expectException(MissingExchangeRateException::class);
        $exchangeRates->getRate('JPY');
    }
}
