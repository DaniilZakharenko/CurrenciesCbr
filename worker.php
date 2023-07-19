<?php

use CurrencyCbr\Repository\CurrencyRepository;
use Predis\Client;

require 'vendor/autoload.php';

$redisConfig = require 'config/redis.php';

$redis = new Client($redisConfig);

$cache = new CurrencyCbr\Cache\RedisCache($redis);
$currencyRepository = new CurrencyRepository($cache);

while (true) {
    $dateString = $redis->brpop('currency_data_collect', 0)[1];
    $date = DateTime::createFromFormat('Y-m-d', $dateString);

    $currencyRepository->updateCurrencyRatesFromExternal($date);
}
