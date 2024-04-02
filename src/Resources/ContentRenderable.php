<?php

namespace bchubbweb\phntm\Resources;

use Stringable;

interface ContentRenderable extends Stringable
{
    public function setContent($content): void;

    public function render(): void;

    public function __toString(): string;
}
