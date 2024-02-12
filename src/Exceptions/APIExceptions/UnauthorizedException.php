<?php

namespace HubSpot\ObjectCollection\Exceptions\APIExceptions;

use HubSpot\ObjectCollection\Exceptions\APIExceptions\APIException as Exception;
use Throwable;

class UnauthorizedException extends Exception
{
    public function __construct($message = "User not authenticated", $error_data = [], $error_code = 401, Throwable $previous = null)
    {
        parent::__construct($message, $error_data, $error_code, $previous);
    }
}
