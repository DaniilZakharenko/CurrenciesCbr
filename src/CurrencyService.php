<?php

namespace CurrencyCbr;

use CurrencyCbr\Cache\CacheInterface;
use CurrencyCbr\Repository\CurrencyRepository;
use DateTime;
use Exception;

class CurrencyService
{

    public function __construct(
        private readonly CurrencyRepository $currencyRepository,
    )
    {
    }

    /**
     * @throws Exception
     */
    public function getCurrencyRateAndDifferent(DateTime $date, string $currencyCode, string $baseCurrencyCode = 'RUB'): CurrencyRateDTO
    {
        $currentRate = $this->getCurrencyRate($date, $currencyCode, $baseCurrencyCode);
        $previousRate = $this->getPreviousRate($date, $currencyCode, $baseCurrencyCode);
        $difference = $currentRate - $previousRate;

        return new CurrencyRateDTO($currencyCode, $baseCurrencyCode, $currentRate, $difference);
    }

    /**
     * @throws Exception
     */
    private function getCurrencyRate(DateTime $date, string $currencyCode, string $baseCurrencyCode = 'RUB', bool $forPrevious = false): float
    {
        if ($baseCurrencyCode !== 'RUB') {
            //делаем кросскурс
            return $this->getCurrencyRateToRub($date, $currencyCode, $forPrevious) / $this->getCurrencyRateToRub($date, $baseCurrencyCode, $forPrevious);
        } else {
            return $this->getCurrencyRateToRub($date, $currencyCode, $forPrevious);
        }
    }

    private function getCurrencyRateToRub(DateTime $date, string $currencyCode, $forPrevious): float
    {
        return $this->currencyRepository->findCurrencyRate($date, $currencyCode, $forPrevious);
    }

    private function getPreviousRate(DateTime $date, string $currencyCode, string $baseCurrencyCode): float
    {
        $previousDate = clone $date;
        $previousDate->modify('-1 day');
        try {
            return $this->getCurrencyRate($previousDate, $currencyCode, $baseCurrencyCode, true);
        } catch (Exception $e) {
            // Если предыдущего курса нет, вернуть 0
            return 0;
        }
    }
}