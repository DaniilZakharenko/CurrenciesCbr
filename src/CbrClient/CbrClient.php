<?php

namespace CurrencyCbr\CbrClient;

use CurrencyCbr\CbrClient\Dto\CollectionCurrencyValueDTO;
use DateTime;
use GuzzleHttp\Client;
use SimpleXMLElement;

class CbrClient
{
    const API_URL = 'http://www.cbr.ru/scripts/XML_daily.asp';

    public function __construct(
        private readonly Client            $httpClient = new Client(),
        private readonly CurrencyTransform $currencyDtoTransform = new CurrencyTransform()
    )
    {
    }

    public function getCurrencyRate(DateTime $date): CollectionCurrencyValueDTO
    {
        $response = $this->httpClient->get(self::API_URL, [
            'query' => [
                'date_req' => $date->format('d/m/Y'),
            ],
        ]);

        $xml = new SimpleXMLElement($response->getBody());
        if(!$xml->attributes()['Date']){
            throw new NotValidDataInCbr();
        }
        return $this->currencyDtoTransform->transformFromCollection($xml);
    }

}