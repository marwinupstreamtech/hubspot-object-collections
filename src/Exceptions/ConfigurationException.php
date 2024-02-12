<?php

namespace HubSpot\ObjectCollection\Exceptions;

use HubSpot\ObjectCollection\Exceptions\BaseException as Exception;
use Throwable;

class ConfigurationException extends Exception
{
    public function __construct($message = "Unable to initialise marwinupstreamtech/hubspot-object-collections", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
