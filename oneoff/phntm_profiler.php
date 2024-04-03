<?php

require_once __DIR__ . '/../../../autoload.php';

use bchubbweb\phntm\Phntm;

header('Content-Type: application/json');

echo Phntm::Redis()->get('phntm_profiling');
