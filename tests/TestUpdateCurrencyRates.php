<?php

namespace App\Tests;


use App\DTO\CurrencyDTO;
use App\Entity\Currency;
use App\Repository\CurrencyRepository;
use App\Service\CurrencyExchangeRateUpdate;
use PHPUnit\Framework\TestCase;

class TestUpdateCurrencyRates extends TestCase
{
    public function test_updating_currency_if_currency_rates_from_db_and_dto_are_equal(): void
    {
        $testCurrencyDTO = new CurrencyDTO('rand', 'RAN', 0.01);
        $testCurrencyEntity = Currency::fromCurrencyDTO($testCurrencyDTO);
        $repository = $this->createMock(CurrencyRepository::class);
        $repository->method('getAllByCodes')->willReturn([$testCurrencyEntity]);
        $currencyExchangeRateUpdate = new CurrencyExchangeRateUpdate($repository);
        $repository->method('save')->willThrowException(new \LogicException('I AM NOT SAVING ANYTHING'));
        $repository->expects(static::never())->method('save');
        $currencyExchangeRateUpdate->updateSingle($testCurrencyDTO);
        $currencyExchangeRateUpdate->updateFromArrayOfDTOs([$testCurrencyDTO]);
    }

    public function test_not_updating_currency_if_currency_rates_from_db_and_dto_are_not_equal(): void
    {
        $testCurrencyDTO = new CurrencyDTO('rand', 'RAN', 0.01);
        $testCurrencyEntity = Currency::fromCurrencyDTO($testCurrencyDTO);
        $testCurrencyEntity->setExchangeRate(
            $testCurrencyEntity->getExchangeRate() + $testCurrencyEntity->getExchangeRate()
        );
        $repository = $this->createMock(CurrencyRepository::class);
        $repository->method('getAllByCodes')->willReturn([$testCurrencyEntity]);
        $currencyExchangeRateUpdate = new CurrencyExchangeRateUpdate($repository);
        $repository->method('save')->willReturn('nothing special');
        $repository->expects(static::atLeastOnce())->method('save');
        $currencyExchangeRateUpdate->updateSingle($testCurrencyDTO);
        $currencyExchangeRateUpdate->updateFromArrayOfDTOs([$testCurrencyDTO]);
    }

    public function test_creating_currency_entity_if_currency_entity_doesnt_exist(): void
    {
        $testCurrencyDTO = new CurrencyDTO('rand', 'RAN', 0.01);
        $testCurrencyEntity = Currency::fromCurrencyDTO($testCurrencyDTO);
        $testCurrencyEntity->setExchangeRate(
            $testCurrencyEntity->getExchangeRate() + $testCurrencyEntity->getExchangeRate()
        );
        $repository = $this->createMock(CurrencyRepository::class);
        $repository->method('getAllByCodes')->willReturn([]);
        $currencyExchangeRateUpdate = new CurrencyExchangeRateUpdate($repository);
        $repository->method('save')->willReturn('nothing special');
        $repository->expects(static::atLeastOnce())->method('save');
        $currencyExchangeRateUpdate->updateSingle($testCurrencyDTO);
        $currencyExchangeRateUpdate->updateFromArrayOfDTOs([$testCurrencyDTO]);
    }
}
