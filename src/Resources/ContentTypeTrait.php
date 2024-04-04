<?php

namespace bchubbweb\phntm\Resources;

trait ContentTypeTrait {

    public string $contentType;

    /**
     * Set the content type
     *
     * @param string $contentType
     * @return void
     */
    public function setContentType(string $contentType): void
    {
        header('Content-Type: ' . $contentType);
        $this->contentType = $contentType;
    }

    /**
     * Return the content type
     *
     * @return string
     */
    public function getContentType(): string
    {
        return $this->contentType;
    }
}
