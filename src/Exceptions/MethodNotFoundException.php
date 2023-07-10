<?php

namespace FilterIt\Exceptions;

use Exception;

class MethodNotFoundException extends Exception
{
    public function __construct($methodName = null, $code = 0)
    {
        parent::__construct($methodName, $code);
        $this->message = " Method '{$methodName}' not found.";
    }
}
