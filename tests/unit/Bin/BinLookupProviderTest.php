<?php

use App\Bin\BinLookupProvider;
use App\Bin\Entity\BinLookup;
use App\Bin\Http\BinListClientInterface;
use App\Serializer\SerializerFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\ResponseInterface;

class BinLookupProviderTest extends TestCase
{
    public function testLookupBin(): void
    {
        // Create mock BinListClientInterface instance
        $binListClientMock = $this->createMock(BinListClientInterface::class);

        // Create mock ResponseInterface instance
        $responseMock = $this->createMock(ResponseInterface::class);
        $responseMock->method('getStatusCode')->willReturn(200);
        $response = '{"country":{"numeric":"208","alpha2":"DK"}}';
        $responseMock->method('getContent')->willReturn($response);

        // Set up expectations for the binListClient->request() method
        $binListClientMock->expects($this->once())
            ->method('request')
            ->with('123456')
            ->willReturn($responseMock);

        // Create mock SerializerFactory instance
        $serializerFactoryMock = $this->createMock(SerializerFactory::class);

        $serializerMock = $this->createMock(\Symfony\Component\Serializer\SerializerInterface::class);
        $serializerMock->method('deserialize')->willReturn(new BinLookup(new \App\Bin\Entity\Country(json_decode($response, true)['country']['alpha2'])));
        $serializerFactoryMock->method('getSerializer')->willReturn($serializerMock);

        // Create BinLookupProvider instance
        $binLookupProvider = new BinLookupProvider($binListClientMock, $serializerFactoryMock);

        // Call lookupBin method with test input
        $binLookup = $binLookupProvider->lookupBin('123456');

        // Assert the result is an instance of BinLookup
        $this->assertInstanceOf(BinLookup::class, $binLookup);

        // Assert the result properties are correctly set
        $this->assertEquals('DK', $binLookup->getCountry()->getAlpha2());
    }
}
