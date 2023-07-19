<?php

namespace CurrencyCbr;

class CurrencyRateDTO
{
    public function __construct(
        private readonly string $currencyCode,
        private readonly string $baseCurrency,
        private readonly float  $rate,
        private readonly float  $difference
    ){}

    public function getDifference(): float
    {
        return $this->difference;
    }

    public function getRate(): float
    {
        return $this->rate;
    }

    public function getCurrencyCode(): string
    {
        return $this->currencyCode;
    }

    public function getBaseCurrency(): string
    {
        return $this->baseCurrency;
    }
}