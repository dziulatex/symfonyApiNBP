<?php

namespace App\Service;


use App\DTO\CurrencyDTO;

interface CurrencyExchangeRateApiInterface
{
    public function getSingleCurrencyByCode(string $currencyCode): CurrencyDTO;

    /**
     * @return CurrencyDTO[]
     */
    public function getAllCurrencies(): array;
}
