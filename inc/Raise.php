<?php

class Raise {
    public static function error(int $code): void {
        echo "<hr>Error raised, code: {$code}<hr>";
        exit();
    }
}
