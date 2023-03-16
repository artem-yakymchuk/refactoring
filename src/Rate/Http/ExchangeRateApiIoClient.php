<?php

declare(strict_types=1);

namespace App\Rate\Http;

use App\Rate\Exception\GetExchangeRatesRequestException;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class ExchangeRateApiIoClient implements ExchangeRateClientInterface
{
    private const SERVICE_URL = 'http://api.exchangeratesapi.io/latest?access_key=%s';

    private HttpClientInterface $httpClient;
    private ClientConfiguration $config;

    public function __construct(HttpClientInterface $httpClient, ClientConfiguration $config)
    {
        $this->httpClient = $httpClient;
        $this->config = $config;
    }

    /**
     * @return ResponseInterface
     * @throws GetExchangeRatesRequestException
     */
    public function request(): ResponseInterface
    {
        try {
            $response = $this->httpClient->request('GET', sprintf(self::SERVICE_URL, $this->config->getAccessKey()));
            $content = json_decode($response->getContent(), true);

            if (!($content['success'] ?? false) || !($content['rates'] ?? false)) {
                //TODO log error
                throw new GetExchangeRatesRequestException($response->getContent(), $response->getStatusCode());
            }
        } catch (ExceptionInterface $exception) {
            throw new GetExchangeRatesRequestException($exception->getMessage(), $exception->getCode(), $exception);
        }

        return $response;
    }
}
