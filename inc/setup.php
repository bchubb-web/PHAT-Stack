<?php
session_start();


include_once(__DIR__.'/utils.php');
include_once(__DIR__.'/Dom.php');
include_once(__DIR__.'/Raise.php');
include_once(__DIR__.'/Secrets.php');

include_once(__DIR__.'/Database.php');
/*$phntm_db = new DB;

$phntm_db->connect('phntm');
$phntm_db->set_table('secrets');*/

include_once(__DIR__.'/HTMX.php');

include_once(__DIR__.'/Router.php');

Router::make_client_params();

if( array_key_exists(0, Router::$client_url_parts) ) {
    if ( Router::$client_url_parts[0] == 'htmx' ) {
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        HTMX::get_api_routes();
    }
}

Router::register_routes(__DIR__.'/../pages/');

