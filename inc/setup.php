<?php
include_once(__DIR__.'/HTMX.php');
include_once(__DIR__.'/Router.php');
include_once(__DIR__.'/functions.php');

require_once(__DIR__.'/../api.php');

Router::generatePages();
