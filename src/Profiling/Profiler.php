<?php

namespace bchubbweb\phntm\Profiling;

class Profiler
{
    protected array $profilingData = [];

    protected static array $entries = [];
    protected static array $names = [];
    public static bool $console = false;

    public static function start($console = false): void
    {
        self::$console = $console;
        self::$entries = [];
        self::$names = [];
        Profiler::flag('start');
    }

    public static function flag($message=''): void
    {
        $time = microtime(true);
        self::$entries[] = new Entry($message, $time);
        self::$names[] = $message;
    }

    public static function dump(): void
    {
        if (self::$console) {
            self::dumpConsole();
        } else {
            self::dumpDialog();
        }
    }

    public static function dumpConsole(): void
    {
        $profileData = '[';

        $size = count(self::$entries);
        for($i=0;$i<$size - 1; $i++)
        {
            $item = self::$entries[$i];
            $timestamp = number_format(self::$entries[$i+1]->timestamp - $item->timestamp, 8);
            $profileData .= "[\"$item->parent\", \"$item->message\", $timestamp],";
        }
        $profileData .= "[\"" . self::$entries[$size-1]->parent . "\", \"" . self::$entries[$size-1]->message . "\", \"n/a\"]]";

        echo "<script>console.table($profileData)</script>";
    }

    public static function dumpDialog(): void
    {
        $size = count(self::$entries);
        echo '<dialog open style="width: 60vw" id="profiler"><table style="width: 100%"><tbody>';
        for($i=0;$i<$size - 1; $i++)
        {
            $item = self::$entries[$i];

            $timestamp = number_format(self::$entries[$i+1]->timestamp - $item->timestamp, 8);
            echo "<tr><td>" . $item->parent . "</td><td>" . $item->message . "</td><td>" . $timestamp . "</td></tr>";
        }
        echo "<tr><td>" . self::$entries[$size-1]->parent . "</td><td>" . self::$entries[$size-1]->message . "</td><td>n/a</td></tr></tbody></table>";
        echo '</dialog>';
    }

    public static function entry($name)
    {
        self::$timing[$name] = microtime(true);
    }
}

