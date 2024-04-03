<?php

namespace bchubbweb\phntm\Routing;

use ErrorException;
use bchubbweb\phntm\Profiling\Profiler;

class ParameterTypeException extends ErrorException
{
    public function __construct(string $message)
    {
        Profiler::flag($message);
    }
}
