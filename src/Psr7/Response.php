<?php

namespace bchubbweb\phntm\Psr7;

use GuzzleHttp\Psr7\Response as Psr7Response;

class Response extends Psr7Response
{
    public function status(int $code): Response
    {
        return $this->withStatus($code);
    }
}
