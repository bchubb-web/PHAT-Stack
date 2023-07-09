<?php
include_once(__DIR__.'/connections.php');
$con = new mysqli($hostname, $username, $password, $database);
unset($hostname, $username, $password, $database);
