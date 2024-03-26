<?php

namespace bchubbweb\phntm\Routing;

use Stringable;

class DynamicParameter implements Stringable
{
    public mixed $value;

    public function __construct(mixed $value, string $type)
    {
        settype($value, $type);
        $this->value = $value;
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }

}
