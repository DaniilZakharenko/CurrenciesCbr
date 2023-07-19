<?php

namespace CurrencyCbr\CbrClient;

use Exception;
use Throwable;

class NotValidDataInCbr extends Exception
{
    public function __construct($code = 0, Throwable $previous = null)
    {
        $message = "Not valid data in CBR";
        parent::__construct($message, $code, $previous);
    }
}