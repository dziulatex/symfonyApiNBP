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


    /**
     * @param CurrencyDTO $currencyDTO
     * @return void
     */
    public function updateSingle(CurrencyDTO $currencyDTO): void
    {
        $this->updateFromArrayOfDTOs([$currencyDTO]);
    }

    /**
     * @param CurrencyDTO[] $currencyDTOs
     * @return void
     */
    public function updateFromArrayOfDTOs(array $currencyDTOs): void
    {
        //this array will have key of currency code, and value of currency entity, if existent in db, or value of currencyDTO if non existent in db
        $currencyDTOorCurrencyEntityArray = [];
        //this array will have currencyDTO indexed by currencyCode, this array will be needed later on to update db entity, or create db entity.
        $currencyDTOIndexedByCurrencyCode = [];
        foreach ($currencyDTOs as $currencyDTO) {
            $this->checkIfNonCurrencyDTOValue($currencyDTO);
            $currencyDTOorCurrencyEntityArray[$currencyDTO->getCurrencyCode()] = $currencyDTO;
            $currencyDTOIndexedByCurrencyCode[$currencyDTO->getCurrencyCode()] = $currencyDTO;
        }
        //getting all existent entities, rather than in loop, its more perfomance friendly. This function will probably look more complicated, but with improved perfomance. (db is always bottlenecking application)
        $existingCurrencyEntities = $this->currencyRepository->getAllByCodes(
            array_keys($currencyDTOorCurrencyEntityArray)
        );
        //updating $currencyDTOorCurrencyEntityArray values with db entities if existent.
        foreach ($existingCurrencyEntities as $existingCurrencyEntity) {
            if ($currencyDTOorCurrencyEntityArray[$existingCurrencyEntity->getCurrencyCode()]) {
                $currencyDTOorCurrencyEntityArray[$existingCurrencyEntity->getCurrencyCode()] = $existingCurrencyEntity;
            }
        }
        //iterating over $currencyDTOorCurrencyEntityArray to update current existent currency entity, or create new, and save.
        foreach ($currencyDTOorCurrencyEntityArray as $currencyDTOorCurrencyEntityObj) {
            if ($currencyDTOorCurrencyEntityObj instanceof CurrencyDTO) {
                $currencyEntity = Currency::fromCurrencyDTO($currencyDTOorCurrencyEntityObj);
                $this->currencyRepository->save($currencyEntity);
            }
            if ($currencyDTOorCurrencyEntityObj instanceof Currency) {
                $currencyEntity = $currencyDTOorCurrencyEntityObj;
                $currencyEntityDTO = $currencyDTOIndexedByCurrencyCode[$currencyEntity->getCurrencyCode()];
                //not changing exchange rate, should not trigger save, we will test that in test later.
                if ($currencyEntity->getExchangeRate() !== $currencyEntityDTO->getExchangeRate()) {
                    $currencyEntity->setExchangeRate($currencyEntityDTO->getExchangeRate());
                    $this->currencyRepository->save($currencyEntity);
                }
            }
        }
    }

    /**
     * @param mixed $currencyDTO
     * @return void
     */
    private function checkIfNonCurrencyDTOValue(mixed $currencyDTO): void
    {
        if (!$currencyDTO instanceof CurrencyDTO) {
            throw new \LogicException(
                'There is non currencyDTO value inside currencyDTOs array in updateFromArrayOfDTOs function'
            );
        }
    }
}
