<?php

namespace Pages\Api;
 
use bchubbweb\phntm\Resources\Layout as LayoutTemplate;

class Layout extends LayoutTemplate
{
    public function __construct() {

        $this->setContentType('application/json');

        $this->setContent('<!-- content /-->');
    }
}

