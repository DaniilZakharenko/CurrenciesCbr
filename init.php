<?php

require __DIR__ . '/vendor/autoload.php';
$redisConfig = require 'config/redis.php';

use CurrencyCbr\Cache\RedisCache;
use CurrencyCbr\CbrClient\NotValidDataInCbr;
use CurrencyCbr\CurrencyService;
use CurrencyCbr\Repository\CurrencyRepository;
use CurrencyCbr\Repository\NotActualRateException;
use CurrencyCbr\Repository\NotFoundCurrencyException;
use Predis\Client as RedisClient;

$redisClient = new RedisClient($redisConfig);

$cache = new RedisCache($redisClient);

$repository = new CurrencyRepository($cache);
$currencyService = new CurrencyService($repository);

echo "Введите дату в формате 'YYYY-MM-DD или нажмините Enter что бы использовать текущую': ".PHP_EOL;
$dateInput = readline();
if(!empty($dateInput)) {
    if($dateInput > date('Y-m-d')){
        echo 'Дата из будущего'.PHP_EOL;
        return;
    }
    $date = DateTime::createFromFormat('Y-m-d', $dateInput);

    if (!$date) {
        echo 'Вы ввели дату не в правильном формате';
        return;
    }
}else{
    $date = new DateTime();
}
echo "Введите код первой валюты: ".PHP_EOL;
$currency1 = readline();

echo "Введите код второй валюты или нажмите Enter, чтобы использовать RUB: ".PHP_EOL;
$currency2 = readline();
if(empty($currency2)) {
    $currency2 = 'RUB';
}

try {
    $rate = $currencyService->getCurrencyRateAndDifferent($date, $currency1, $currency2);
    printf(

        "На дату %s, курс обмена %s к %s равен %.4f. Разница с предыдущим торговым днем: %.4f.\n",
        $date->format('Y-m-d'), $currency1, $currency2, $rate->getRate(), $rate->getDifference()
    );
} catch (NotFoundCurrencyException|NotActualRateException|NotValidDataInCbr $notFoundException) {
    echo $notFoundException->getMessage() . PHP_EOL;
} catch (Exception $exception) {
    echo 'Unknown error' . PHP_EOL;
    throw $exception;
}
