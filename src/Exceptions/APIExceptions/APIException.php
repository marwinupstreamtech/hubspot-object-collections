<?php

namespace HubSpot\ObjectCollection\Exceptions\APIExceptions;

use HubSpot\ObjectCollection\Exceptions\BaseException as Exception;
use Throwable;

class APIException extends Exception
{
    protected $errorData, $errorCode, $message;

    public function __construct($message = "An error occured processing the request", $error_data = [], $error_code = 0, Throwable $previous = null)
    {
        $this->errorData = $error_data;
        $this->errorCode = $error_code;
        $this->message = $message;

        parent::__construct($message, $error_code, $previous);
    }

    public function getErrorData() {
        return $this->errorData;
    }

    public function getErrorCode() {
        return $this->errorCode;
    }

    public function getErrorMessage() {
        return $this->message;
    }
}
