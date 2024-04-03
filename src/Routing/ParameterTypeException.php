<?php

namespace bchubbweb\phntm\Routing;

use ErrorException;
use bchubbweb\phntm\Phntm;

class ParameterTypeException extends ErrorException
{
    public function __construct(string $message)
    {
        Phntm::Profile()->flag($message);
    }
}
