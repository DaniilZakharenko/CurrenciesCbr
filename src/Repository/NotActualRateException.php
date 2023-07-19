<?php

namespace CurrencyCbr\Repository;

use Exception;
use Throwable;

class NotActualRateException extends Exception
{
    public function __construct($code = 0, Throwable $previous = null)
    {
        $message = "Not actual rate for this date.";
        parent::__construct($message, $code, $previous);
    }
}