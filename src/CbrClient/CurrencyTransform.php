<?php

namespace CurrencyCbr\CbrClient;

use CurrencyCbr\CbrClient\Dto\CollectionCurrencyValueDTO;
use CurrencyCbr\CbrClient\Dto\CurrencyValueDTO;
use DateTime;
use SimpleXMLElement;

class CurrencyTransform
{
    public function transform(SimpleXMLElement $simpleXMLElement): CurrencyValueDTO
    {
        return new CurrencyValueDTO(
            $simpleXMLElement->CharCode,
            $this->getFloat($simpleXMLElement->Value)/(int) $simpleXMLElement->Nominal
        );

    }

    /**
     * @return CollectionCurrencyValueDTO
     */
    public function transformFromCollection(SimpleXMLElement $simpleXMLElement): CollectionCurrencyValueDTO
    {
        $date = DateTime::createFromFormat('d.m.Y',$simpleXMLElement->attributes()['Date']);
        $collectionDto = [];
        foreach ($simpleXMLElement->Valute as $valute) {
            $collectionDto[] = $this->transform($valute);
        }
        return new CollectionCurrencyValueDTO($date, $collectionDto);
    }
    private function getFloat(string $string): float
    {
        return (float)str_replace(',', '.', $string);

    }
}