<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

include_once(__DIR__.'/HTMX.php');
foreach (glob(__DIR__."/../api/*.php") as $filename) {
    include $filename;
}
include_once(__DIR__.'/Router.php');
include_once(__DIR__.'/functions.php');



Router::generatePages();
