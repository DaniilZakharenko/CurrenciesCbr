<?php

namespace CurrencyCbr\CbrClient\Dto;

class CurrencyValueDTO
{
    public function __construct(private readonly string $currencyCode, private readonly float $rate)
    {}

    public function getRate(): float
    {
        return $this->rate;
    }

    public function getCurrencyCode(): string
    {
        return $this->currencyCode;
    }
}