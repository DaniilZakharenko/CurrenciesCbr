<?php

namespace CurrencyCbr\Repository;

use Exception;
use Throwable;

class NotFoundCurrencyException extends Exception
{
    public function __construct(string $currencyCode, $code = 0, Throwable $previous = null)
    {
        $message = "Currency code {$currencyCode} not found.";
        parent::__construct($message, $code, $previous);
    }
}