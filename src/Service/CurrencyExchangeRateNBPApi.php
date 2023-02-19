<?php

namespace App\Service;

use App\ApiRequest\GetAllCurrenciesFromNBPApiRequest;
use App\ApiRequest\GetSingleCurrencyFromNBPApiRequest;
use App\DTO\CurrencyDTO;

class CurrencyExchangeRateNBPApi implements CurrencyExchangeRateApiInterface
{
    private GetSingleCurrencyFromNBPApiRequest $getSingleCurrencyFromNBPApiRequest;
    private GetAllCurrenciesFromNBPApiRequest $getAllCurrenciesFromNBPApiRequest;

    /**
     * @param GetSingleCurrencyFromNBPApiRequest $getSingleCurrencyFromNBPApiRequest
     * @param GetAllCurrenciesFromNBPApiRequest $getAllCurrenciesFromNBPApiRequest
     */
    public function __construct(
        GetSingleCurrencyFromNBPApiRequest $getSingleCurrencyFromNBPApiRequest,
        GetAllCurrenciesFromNBPApiRequest $getAllCurrenciesFromNBPApiRequest
    ) {
        $this->getSingleCurrencyFromNBPApiRequest = $getSingleCurrencyFromNBPApiRequest;
        $this->getAllCurrenciesFromNBPApiRequest = $getAllCurrenciesFromNBPApiRequest;
    }

    /**
     * @param string $currencyCode
     * @return CurrencyDTO
     */
    public function getSingleCurrencyByCode(string $currencyCode): CurrencyDTO
    {
        return $this->getSingleCurrencyFromNBPApiRequest->getSingleCurrency($currencyCode);
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
        return $this->getAllCurrenciesFromNBPApiRequest->getAllCurrencies();
    }
}
