<?php

namespace App\Tests;


use App\ApiRequest\GetAllCurrenciesFromNBPApiRequest;
use App\ApiRequest\GetSingleCurrencyFromNBPApiRequest;
use App\DTO\CurrencyDTO;
use App\Entity\Currency;
use App\Repository\CurrencyRepository;
use App\Service\CurrencyExchangeRateNBPApi;
use App\Service\CurrencyExchangeRateUpdate;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class TestCurrencyExchangeRateNBPApi extends TestCase
{
    public function test_get_single_and_all_currencies(): void
    {
        $getSingleCurrencyFromNBPApiRequest = $this->setupGetSingleCurrencyFromNBPApiRequestMock();
        $getAllCurrenciesFromNBPApiRequest = $this->setupGetAllCurrenciesFromNBPApiRequestMock();
        $currencyExchangeRateNBPApi = new CurrencyExchangeRateNBPApi(
            $getSingleCurrencyFromNBPApiRequest,
            $getAllCurrenciesFromNBPApiRequest
        );

        $currencyDTOToMatch = new CurrencyDTO('bat (Tajlandia)', 'THB', 0.1297);
        $this->allCurrenciesTestApiNBP($currencyExchangeRateNBPApi, $currencyDTOToMatch);
        $this->singleCurrencyTestApiNBP($currencyExchangeRateNBPApi, $currencyDTOToMatch);
    }

    /**
     * @return GetSingleCurrencyFromNBPApiRequest
     */
    public function setupGetSingleCurrencyFromNBPApiRequestMock(): GetSingleCurrencyFromNBPApiRequest
    {
        $callback = function ($method, $url, $options) {
            return new MockResponse(
                '{
        "table": "A",
        "currency": "bat (Tajlandia)",
        "code": "THB",
        "rates": [
            {   
            "no": "034/A/NBP/2023",
            "effectiveDate": "2023-02-17",
            "mid": 0.1297
            }
        ]
        }'
            );
        };
        $client = new MockHttpClient($callback);
        $getSingleCurrencyFromNBPApiRequest = new GetSingleCurrencyFromNBPApiRequest($client);
        return $getSingleCurrencyFromNBPApiRequest;
    }

    /**
     * @return GetAllCurrenciesFromNBPApiRequest
     */
    public function setupGetAllCurrenciesFromNBPApiRequestMock(): GetAllCurrenciesFromNBPApiRequest
    {
        $callback = function ($method, $url, $options) {
            return new MockResponse(
                '[
    {
        "table": "A",
        "no": "034/A/NBP/2023",
        "effectiveDate": "2023-02-17",
        "rates": [
            {
                "currency": "bat (Tajlandia)",
                "code": "THB",
                "mid": 0.1297
            },
            {
                "currency": "dolar amerykański",
                "code": "USD",
                "mid": 4.4888
            }
        ]
    }
]'
            );
        };
        $client = new MockHttpClient($callback);
        $getAllCurrenciesFromNBPApiRequest = new GetAllCurrenciesFromNBPApiRequest($client);
        return $getAllCurrenciesFromNBPApiRequest;
    }

    /**
     * @param CurrencyExchangeRateNBPApi $currencyExchangeRateNBPApi
     * @param CurrencyDTO $currencyDTOToMatch
     * @return void
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function allCurrenciesTestApiNBP(
        CurrencyExchangeRateNBPApi $currencyExchangeRateNBPApi,
        CurrencyDTO $currencyDTOToMatch
    ): void {
        $allCurrenciesArray = $currencyExchangeRateNBPApi->getAllCurrencies();
        $currencyThaiFromGetAllCurrencies = $allCurrenciesArray[0];
        $this->assertEquals(
            $currencyThaiFromGetAllCurrencies->getExchangeRate(),
            $currencyDTOToMatch->getExchangeRate()
        );
        $this->assertEquals($currencyThaiFromGetAllCurrencies->getName(), $currencyDTOToMatch->getName());
        $this->assertEquals(
            $currencyThaiFromGetAllCurrencies->getCurrencyCode(),
            $currencyDTOToMatch->getCurrencyCode()
        );
    }

    /**
     * @param CurrencyExchangeRateNBPApi $currencyExchangeRateNBPApi
     * @param CurrencyDTO $currencyDTOToMatch
     * @return void
     */
    public function singleCurrencyTestApiNBP(
        CurrencyExchangeRateNBPApi $currencyExchangeRateNBPApi,
        CurrencyDTO $currencyDTOToMatch
    ): void {
        $entityFromGetSingleCurrencyByCode = $currencyExchangeRateNBPApi->getSingleCurrencyByCode('THB');
        $this->assertEquals(
            $entityFromGetSingleCurrencyByCode->getExchangeRate(),
            $currencyDTOToMatch->getExchangeRate()
        );
        $this->assertEquals($entityFromGetSingleCurrencyByCode->getName(), $currencyDTOToMatch->getName());
        $this->assertEquals(
            $entityFromGetSingleCurrencyByCode->getCurrencyCode(),
            $currencyDTOToMatch->getCurrencyCode()
        );
    }

}
