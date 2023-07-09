<?php
$con = false;
if (class_exists('mysqli')){
    include_once(__DIR__.'/connections.php');
    $con = new mysqli($hostname, $username, $password, $database);
}
