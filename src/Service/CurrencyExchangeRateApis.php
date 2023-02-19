<?php

namespace App\Service;

use App\DTO\CurrencyDTO;

class CurrencyExchangeRateApis
{
    private CurrencyExchangeRateNBPApi $currencyExchangeRateNBPApi;

    /**
     * @param CurrencyExchangeRateNBPApi $currencyExchangeRateNBPApi
     */
    public function __construct(CurrencyExchangeRateNBPApi $currencyExchangeRateNBPApi)
    {
        $this->currencyExchangeRateNBPApi = $currencyExchangeRateNBPApi;
    }

    /**
     * @return CurrencyDTO[]
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    //this method can be easily extended, to get multiple types of currency rates from more apis, just currencyDTO should be extended with some "sourceType" attribute or something, along with db entity.
    // i think sourceType, if added to this symfony app, should be enum or declared static somewhere.
    //later on all current calls for getAllByCodes, should be swapped with getAllByCodesAndType, and i think everything would be gucci.And if you would add sourceType, you should clear db, or make migration to add sourceType for earlier records which were createn without that column.
    public function getAllCurrenciesFromAllApis(): array
    {
        return $this->currencyExchangeRateNBPApi->getAllCurrencies();
    }
}
