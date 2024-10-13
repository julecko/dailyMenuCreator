<?php

namespace App\Exceptions;

use Exception;

class FoodException extends Exception
{
    public function __construct($message = "Default Custom Exception Message", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
