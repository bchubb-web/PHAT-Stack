<?php

namespace bchubbweb\phntm\Routing;

use Stringable;
use bchubbweb\phntm\Routing\ParameterTypeException;

class DynamicParameter implements Stringable
{
    public mixed $value;

    public function __construct(mixed $value, string $type)
    {
        $this->setType($value, $type);
        $this->value = $value;
    }

    protected function setType(mixed &$value, string $type) {
        if ('int' === $type) {
            if (!is_numeric($value)) {
                throw new ParameterTypeException("Type Error: Dynamic parameter {$value} does not match type {$type}.");
            }
        }

        settype($value, $type);
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }

}
