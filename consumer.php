<?php
use Predis\Client;

require 'vendor/autoload.php';

$redisConfig = require 'config/redis.php';
$redis = new Client($redisConfig);

for ($i = 0; $i < 180; $i++) {
    $date = (new DateTime())->modify("-$i days")->format('Y-m-d');
    $redis->lpush('currency_data_collect', [$date]);
}
