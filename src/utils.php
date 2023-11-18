<?php

/**
 * @file
 * Utility functions for general use
 */

namespace Bchubb\Phntm;

/**
 * Dumps the content of a variable and formats
 *
 * @param mixed $thing the value to be dumped
 *
 * @return void
 */
function dump(mixed $thing): void
{
    echo '<pre>';
    var_dump($thing);
    echo '</pre>';
}

/**
 * Dumps the content of a variable and formats
 *
 * @param string $url the url to be redirected to
 *
 * @return void
 */
function redirect(string $url)
{
    header('location: ' . $url);
}
