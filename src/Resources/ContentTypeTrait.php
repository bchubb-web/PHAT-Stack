<?php

namespace bchubbweb\phntm\Resources;

trait ContentTypeTrait {

    public string $contentType;

    public function setContentType(string $contentType): void
    {
        header('Content-Type: ' . $contentType);
        $this->contentType = $contentType;
    }
    public function getContentType(): string
    {
        return $this->contentType;
    }
}
