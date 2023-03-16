<?php

declare(strict_types=1);

namespace App\Tests\Bin\Http;

use App\Bin\Exception\BinLookupException;
use App\Bin\Http\BinListClient;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class BinListClientTest extends TestCase
{
    /** @var HttpClientInterface|MockObject */
    private $httpClientMock;

    /** @var BinListClient */
    private BinListClient $binListClient;

    protected function setUp(): void
    {
        $this->httpClientMock = $this->createMock(HttpClientInterface::class);
        $this->binListClient = new BinListClient($this->httpClientMock);
    }

    public function testRequestWithValidBin(): void
    {
        $bin = '123456';

        $responseMock = $this->createMock(ResponseInterface::class);
        $responseMock->expects($this->once())
            ->method('getStatusCode')
            ->willReturn(200);

        $this->httpClientMock->expects($this->once())
            ->method('request')
            ->with('GET', $this->isType('string'))
            ->willReturn($responseMock);

        $response = $this->binListClient->request($bin);

        $this->assertSame($responseMock, $response);
    }

    public function testRequestWithInvalidBin(): void
    {
        $bin = '123456';

        $responseMock = $this->createMock(ResponseInterface::class);
        $responseMock->expects($this->exactly(2))
            ->method('getStatusCode')
            ->willReturn(404);

        $responseMock->expects($this->once())
            ->method('getContent')
            ->willReturn('Not found');

        $this->httpClientMock->expects($this->once())
            ->method('request')
            ->with('GET', $this->isType('string'))
            ->willReturn($responseMock);

        $this->expectException(BinLookupException::class);
        $this->expectExceptionMessage('Not found');
        $this->expectExceptionCode(404);

        $this->binListClient->request($bin);
    }

    public function testRequestWithHttpClientException(): void
    {
        $bin = '123456';

        $exceptionMock = $this->createMock(ExceptionInterface::class);

        $this->httpClientMock->expects($this->once())
            ->method('request')
            ->with('GET',$this->isType('string'))
            ->willThrowException($exceptionMock);

        $this->expectException(BinLookupException::class);
        $this->expectExceptionMessage($exceptionMock->getMessage());
        $this->expectExceptionCode($exceptionMock->getCode());

        $this->binListClient->request($bin);
    }
}
