<?php

declare(strict_types=1);

namespace App\Bin\Http;

use App\Bin\Exception\BinLookupException;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class BinListClient implements BinListClientInterface
{
    private const LOOKUP_SERVICE_URL = 'https://lookup.binlist.net/%s';

    private HttpClientInterface $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @param string $bin
     * @return ResponseInterface
     * @throws BinLookupException
     */
    public function request(string $bin): ResponseInterface
    {
        try {
            $response = $this->httpClient->request('GET', sprintf(self::LOOKUP_SERVICE_URL, $bin));

            if ($response->getStatusCode() !== 200) {
                //TODO log error
                throw new BinLookupException($response->getContent(), $response->getStatusCode());
            }
        } catch (ExceptionInterface $exception) {
            //TODO log error
            throw new BinLookupException($exception->getMessage(), $exception->getCode(), $exception);
        }

        return $response;
    }
}
