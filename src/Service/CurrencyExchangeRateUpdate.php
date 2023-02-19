<?php

namespace App\Service;

use App\ApiRequest\GetAllCurrenciesFromNBPApiRequest;
use App\ApiRequest\GetSingleCurrencyFromNBPApiRequest;
use App\DTO\CurrencyDTO;
use App\Repository\CurrencyRepository;

class CurrencyExchangeRateUpdate implements CurrencyExchangeRateUpdateInterface
{
    private CurrencyRepository $currencyRepository;

    /**
     * @param CurrencyRepository $currencyRepository
     */
    public function __construct(CurrencyRepository $currencyRepository)
    {
        $this->currencyRepository = $currencyRepository;
    }


    public function updateSingle(CurrencyDTO $currencyDTO)
    {
        // TODO: Implement updateSingle() method.
    }

    public function updateFromArrayOfDTOs(array $currencyDTOs)
    {
        // TODO: Implement updateFromArrayOfDTOs() method.
    }
}
