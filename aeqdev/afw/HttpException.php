<?php

namespace aeqdev\afw;

class HttpException extends \Exception
{

    function __construct($code = 500, $message = null, $previous = null)
    {
        http_response_code($code);
        parent::__construct($message, $code, $previous);
    }

}
