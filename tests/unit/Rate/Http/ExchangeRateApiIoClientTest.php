<?php

declare(strict_types=1);

namespace App\Tests\Rate\Http;

use App\Rate\Exception\GetExchangeRatesRequestException;
use App\Rate\Http\ClientConfiguration;
use App\Rate\Http\ExchangeRateApiIoClient;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class ExchangeRateApiIoClientTest extends TestCase
{
    public function testRequestReturnsResponse(): void
    {
        $httpClientMock = $this->createMock(HttpClientInterface::class);
        $clientConfig = new ClientConfiguration('test_access_key');
        $client = new ExchangeRateApiIoClient($httpClientMock, $clientConfig);

        $responseMock = $this->createMock(ResponseInterface::class);
        $responseMock
            ->method('getContent')
            ->willReturn('{"success":true,"rates":{"USD":1.1234}}');
        $responseMock->method('getStatusCode')->willReturn(200);
        $httpClientMock
            ->method('request')
            ->willReturn($responseMock);

        $response = $client->request();

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('{"success":true,"rates":{"USD":1.1234}}', $response->getContent());
    }

    public function testRequestThrowsExceptionOnFailedRequest(): void
    {
        $this->expectException(GetExchangeRatesRequestException::class);
        $this->expectExceptionCode(400);

        $httpClientMock = $this->createMock(HttpClientInterface::class);
        $clientConfig = new ClientConfiguration('test_access_key');
        $client = new ExchangeRateApiIoClient($httpClientMock, $clientConfig);

        $responseMock = $this->createMock(ResponseInterface::class);
        $responseMock
            ->method('getContent')
            ->willReturn('{"success":false,"error":{"code":400,"type":"foo","info":"bar"}}');
        $responseMock
            ->method('getStatusCode')
            ->willReturn(400);
        $httpClientMock
            ->method('request')
            ->willReturn($responseMock);

        $client->request();
    }
}
