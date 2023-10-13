<?php

class Secrets {
    public static function get(string $secret): array | false {
        $file = __DIR__."/../.secrets/{$secret}.txt";
        if (!is_file($file)) {
            Raise::error(500);
            return false;
        }
        return explode(" ",file_get_contents($file));
    }
}
