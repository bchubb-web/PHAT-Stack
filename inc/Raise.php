<?php

class Raise {
    private static $notifications = [];

    public static function error(int $code, string $message, string $file="", int $line=-1, string $context=""): void {
        self::register($code, $message, "red", $file, $line);
    }

    public static function warning(int $code): void {
        self::register($code, "Warning raised", "orange");
    }

    public static function success(int $code): void {
        self::register($code, "Success", "green");
    }

    public static function notify():void { ?>
        <section class="absolute top-2 right-2 z-10 flex flex-col gap-3">
        
        <?php foreach(self::$notifications as $notification) : ?>
            <div class="w-80 min-h-24 border-4 p-2 border-red-600 bg-white rounded-md break-words">
                <h4 class="ml-3 text-red-600 font-bold"><?= $notification['code'] ?></h4>
                <hr>
                <p><?= $notification['message'] ?></p>
                <hr>
                <p><?= $notification['file']."|".$notification['line'] ?></p>
            </div>
        <?php endforeach; ?>
        </section>
    <?php }

    private static function register(int $code, string $message, string $type, $file="", $line=-1){
        self::$notifications[] = [
            "code" => $code,
            "message" => $message,
            "type" => $type,
            "file" => $file,
            "line" => $line,
        ];
    }
}

set_error_handler("Raise::error");
