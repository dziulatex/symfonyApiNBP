<?php

namespace App\ApiRequest;


use App\DTO\CurrencyDTO;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\Service\Attribute\Required;

/**
 *
 */
class GetAllCurrenciesFromNBPApiRequest
{
    /** @var string */
    private $urlApiNBP;
    private HttpClientInterface $client;

    /**
     * @param HttpClientInterface $client
     */
    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @return CurrencyDTO[]
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function getAllCurrencies(): array
    {
        $contentArray = $this->getResponseAsArray();
        return $this->buildReturnResponse($contentArray);
    }

    #[Required]
    public function setUrlApiNBP(string $urlApiNBP): void
    {
        $this->urlApiNBP = $urlApiNBP;
    }

    /**
     * @param array $contentArray
     * @return CurrencyDTO[]
     */
    public function buildReturnResponse(array $contentArray): array
    {
        $arrayOfCurrencyDTO = [];
        foreach ($contentArray['rates'] as $currencyRate) {
            $arrayOfCurrencyDTO[] = new CurrencyDTO(
                $currencyRate['currency'],
                $currencyRate['code'],
                $currencyRate['mid']
            );
        }
        return $arrayOfCurrencyDTO;
    }

    /**
     * @return mixed
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function getResponseAsArray(): mixed
    {
        $response = $this->client->request(
            'GET',
            $this->urlApiNBP . '/exchangerates/tables/A/'
        );

        $contentArray = $response->toArray()[0];
        return $contentArray;
    }
}
