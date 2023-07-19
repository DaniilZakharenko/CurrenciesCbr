<?php

namespace CurrencyCbr\CbrClient\Dto;

use DateTime;

class CollectionCurrencyValueDTO
{
    public function __construct(
        private readonly DateTime $dateCurrencyActual,
        private readonly array $currenciesValueDTO
    )
    {}

    /**
     * @return DateTime
     */
    public function getDateCurrencyActual(): DateTime
    {
        return $this->dateCurrencyActual;
    }

    public function getCurrenciesValueDTO(): array
    {
        return $this->currenciesValueDTO;
    }

}