<?php

namespace bchubbweb\phntm\Profiling;

class Entry {

    public mixed $parent = '';
    
    public function __construct(public string $message, public float $timestamp)
    {
        // get the name of the function that called this function
        // get classname without namespace
        $backtrace = debug_backtrace();
        $backtraceIndex = min(2, count($backtrace) -1);
        $class = explode('\\', $backtrace[$backtraceIndex]['class']);
        $classname = end($class);
        $this->parent = $classname . '::' . debug_backtrace()[$backtraceIndex]['function'];
    }
}
