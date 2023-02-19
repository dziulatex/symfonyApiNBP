<?php

namespace App\Service;

use App\ApiRequest\GetAllCurrenciesFromNBPApiRequest;
use App\ApiRequest\GetSingleCurrencyFromNBPApiRequest;
use App\DTO\CurrencyDTO;
use App\Entity\Currency;
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
        $currencyEntity = $this->currencyRepository->getSingleByCode($currencyDTO->getCurrencyCode());
        if ($currencyEntity) {
            $currencyEntity->setExchangeRate($currencyDTO->getExchangeRate());
        } else {
            $currencyEntity = new Currency(
                $currencyDTO->getName(),
                $currencyDTO->getCurrencyCode(),
                $currencyDTO->getExchangeRate()
            );
        }
        $this->currencyRepository->save($currencyEntity);
    }

    public function updateFromArrayOfDTOs(array $currencyDTOs)
    {
        // TODO: Implement updateFromArrayOfDTOs() method.
    }
}
