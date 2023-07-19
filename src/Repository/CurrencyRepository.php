<?php

namespace CurrencyCbr\Repository;

use CurrencyCbr\Cache\CacheInterface;
use CurrencyCbr\CbrClient\CbrClient;
use DateTime;

class CurrencyRepository
{
    const TEMPLATE_KEY_FOR_DAY_LOAD = 'loaded_currency_rate_for_day_%s';
    const FORMAT_FOR_ACTUAL_DAY = 'Y_m_d';
    public function __construct(
        private readonly CacheInterface $cache,
        private readonly CbrClient $cbrClient = new CbrClient(),
    ){}

    private function getKeyForDay(DateTime $date): string
    {
        return sprintf(self::TEMPLATE_KEY_FOR_DAY_LOAD, $date->format('Y_m_d'));
    }

    private function getKeyForCurrencyRate($codeCurrency, $date): string
    {
        return "currency_rate_{$codeCurrency}_RUB_{$date->format('Y_m_d')}";
    }
    public function updateCurrencyRatesFromExternal(DateTime $date): void
    {
        $collectionDto = $this->cbrClient->getCurrencyRate($date);

        //Если даты не совпадают, то смысла в сохранени данных не торгового дня нет
        if($date->format(self::FORMAT_FOR_ACTUAL_DAY) !== $collectionDto->getDateCurrencyActual()->format(self::FORMAT_FOR_ACTUAL_DAY)){

            //Проверяем, если дата больше или равна текущему дню, показываем ошибку.
            if((new DateTime())->format(self::FORMAT_FOR_ACTUAL_DAY) <= $date->format(self::FORMAT_FOR_ACTUAL_DAY)){
                throw new NotActualRateException();
            }

            //ставим дату куда обратиться

            $this->cache->set($this->getKeyForDay($date), $collectionDto->getDateCurrencyActual()->format(self::FORMAT_FOR_ACTUAL_DAY));
            return;
        }

        foreach ($collectionDto->getCurrenciesValueDTO() as $currencyValueDTO){
            $this->cache->set($this->getKeyForCurrencyRate($currencyValueDTO->getCurrencyCode(), $date), $currencyValueDTO->getRate());
        }

        $this->cache->set($this->getKeyForDay($date), $date->format(self::FORMAT_FOR_ACTUAL_DAY));
    }

    public function findCurrencyRate(DateTime $date, string $currencyCode, $forPrevious):float
    {
        $loadedDataForDay = $this->cache->get($this->getKeyForDay($date));
        //Нету дня обновляем текущий день
        if(!$loadedDataForDay){
            $this->updateCurrencyRatesFromExternal($date);
            //После загрузки требуется еще раз зайти, вдруг дата не актуальная
            return $this->findCurrencyRate($date, $currencyCode, $forPrevious);
        }
        $dateLoaded = DateTime::createFromFormat(self::FORMAT_FOR_ACTUAL_DAY, $loadedDataForDay);

        //Значит дата не актуальная, и мы ее не сохраняли, ищем в актуальном
        if($dateLoaded->format(self::FORMAT_FOR_ACTUAL_DAY) !== $date->format(self::FORMAT_FOR_ACTUAL_DAY)){
            if($forPrevious){
                //В случая если мы ищем дату предыщуего торгового дня, нам требуется убрать еще день
                $dateLoaded->modify('-1 days');
            }
            //Даты не совпадали, идем по дате актуальной
            return $this->findCurrencyRate($dateLoaded, $currencyCode, $forPrevious);
        }

        $currencyValue = $this->cache->get($this->getKeyForCurrencyRate($currencyCode,$date));
        if(!$currencyValue){
            throw new NotFoundCurrencyException($currencyCode);
        }

        return $currencyValue;
    }
}