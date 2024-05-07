<?php

namespace App\Exceptions;

use Exception;

class ApiException extends Exception
{
    public static function connectionError($message = 'Error connecting to the API')
    {
        return new static($message);
    }

    public static function httpError($message = 'Error fetching data from the API')
    {
        return new static($message);
    }
}
