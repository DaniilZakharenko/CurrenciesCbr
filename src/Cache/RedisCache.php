<?php

namespace CurrencyCbr\Cache;

use Predis\Client;

class RedisCache implements CacheInterface
{
    private $redis;

    public $prefix = 'currency_';

    public function __construct(Client $redis)
    {
        $this->redis = $redis;
    }

    public function get(string $key)
    {
        $value = $this->redis->get($this->prefix . $key);

        return $value !== null ? unserialize($value) : null;
    }

    public function set(string $key, $value): void
    {
        $this->redis->set($this->prefix . $key, serialize($value));
    }

    public function has(string $key): bool
    {
        return $this->redis->exists($this->prefix . $key) > 0;
    }
}