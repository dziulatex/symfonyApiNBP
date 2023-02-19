<?php

namespace App\Service;


use App\DTO\CurrencyDTO;

interface CurrencyExchangeRateUpdateInterface
{
    public function updateSingle(CurrencyDTO $currencyDTO);


    public function updateFromArrayOfDTOs(array $currencyDTOs);
}
