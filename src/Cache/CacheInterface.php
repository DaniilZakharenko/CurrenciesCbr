<?php

namespace CurrencyCbr\Cache;

interface CacheInterface
{
    public function get(string $key);

    public function set(string $key, $value): void;

    public function has(string $key): bool;
}