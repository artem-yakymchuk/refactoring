<?php

use App\Rate\Entity\ExchangeRate;
use App\Rate\Entity\ExchangeRates;
use App\Rate\Exception\GetExchangeRatesRequestException;
use App\Rate\Http\ExchangeRateClientInterface;
use App\Rate\ExchangeRatesProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\ResponseInterface;

class ExchangeRatesProviderTest extends TestCase
{
    private $exchangeRateClient;

    protected function setUp(): void
    {
        $this->exchangeRateClient = $this->createMock(ExchangeRateClientInterface::class);
    }

    public function testGetExchangeRatesReturnsExchangeRatesObject(): void
    {
        $exchangeRatesProvider = new ExchangeRatesProvider($this->exchangeRateClient);
        $exchangeRates = new ExchangeRates(
            ExchangeRate::create('USD', 1.23),
            ExchangeRate::create('EUR', 1.34),
            ExchangeRate::create('GBP', 1.45)
        );
        $response = $this->createMock(ResponseInterface::class);
        $response
            ->method('getContent')
            ->willReturn(json_encode([
                'rates' => [
                    'USD' => 1.23,
                    'EUR' => 1.34,
                    'GBP' => 1.45
                ]
            ]));

        $this->exchangeRateClient
            ->method('request')
            ->willReturn($response);
        $this->assertEquals($exchangeRates, $exchangeRatesProvider->getExchangeRates());
    }

    public function testGetExchangeRatesThrowsGetExchangeRatesRequestException(): void
    {
        $exchangeRatesProvider = new ExchangeRatesProvider($this->exchangeRateClient);
        $this->exchangeRateClient
            ->method('request')
            ->willThrowException(new GetExchangeRatesRequestException());
        $this->expectException(GetExchangeRatesRequestException::class);
        $exchangeRatesProvider->getExchangeRates();
    }
}
