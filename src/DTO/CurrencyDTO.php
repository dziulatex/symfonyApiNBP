<?php

namespace App\DTO;

class CurrencyDTO
{
    public function __construct(
        private readonly string $name,
        private readonly string $currencyCode,
        private readonly float $exchangeRate
    ) {
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getCurrencyCode(): string
    {
        return $this->currencyCode;
    }

    /**
     * @return float
     */
    public function getExchangeRate(): float
    {
        return $this->exchangeRate;
    }

}
