<?php

namespace bchubbweb\phntm\Profiling;

use bchubbweb\phntm\Phntm;

class Profiler
{
    protected array $profilingData = [];
    protected static array $entries = [];
    protected static array $names = [];
    public static bool $console = false;
    public static bool $started = false;

    public function start($console = false): void
    {
        self::$console = $console;
        self::$entries = [];
        self::$names = [];
        self::$started = true;
        $this->flag('Profiling started');
    }

    public function stop(): void
    {
        $this->flag('Profiling ended');

        $encoded = json_encode($this->exportEntries());
        Phntm::Redis()->set('phntm_profiling', $encoded, 'EX', 300);
        self::$started = false;
    }

    public function exportEntries(): array
    {
        $entries = [];
        foreach (self::$entries as $entry) {
            $entries[] = $entry->export();
        }
        return $entries;
    }

    public function flag($message=''): void
    {
        if (!self::$started) {
            return;
        }
        $time = microtime(true);
        self::$entries[] = new Entry($message, $time);
        self::$names[] = $message;
    }

    public static function dump(): string | bool
    {
        if (!self::$started) {
            return '<!-- Profiler -->';
        }
        return self::$console ? self::getConsole() : self::getDialog();
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

    public static function getConsole(): string
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

        return "<script>console.table($profileData)</script>";
    }

    public static function getDialog(): string
    {
        $size = count(self::$entries);
        $profileData = '<dialog open style="min-width: 60vw" id="profiler"><table style="width: 100%"><tbody>';
        for($i=0;$i<$size - 1; $i++)
        {
            $item = self::$entries[$i];
            $timestamp = number_format(self::$entries[$i+1]->timestamp - $item->timestamp, 8);
            $profileData .= "<tr><td>" . $item->parent . "</td><td>" . $item->message . "</td><td>" . $timestamp . "</td></tr>";
        }
        $profileData .= "<tr><td>" . self::$entries[$size-1]->parent . "</td><td>" . self::$entries[$size-1]->message . "</td><td>n/a</td></tr></tbody></table></dialog>";

        return $profileData;
    }

    public function getScript(): string
    {
        $script = <<<JS
        <script>
        const onDOMContentLoaded = (init) => {
          if (['complete', 'interactive', 'loaded'].includes(document.readyState)) {
            init();
          } else {
            document.addEventListener('DOMContentLoaded', init);
          }
        };
        const findComments = function(el) {
            var arr = [];
            for(var i = 0; i < el.childNodes.length; i++) {
                var node = el.childNodes[i];
                if(node.nodeType === 8) {
                    arr.push(node);
                } else {
                    arr.push.apply(arr, findComments(node));
                }
            }
            return arr;
        };

        const getEntries = async () => {
            entries = await fetch('/api/profiler');
            return entries;
        };

        const generateProfilerTable = (data) => {
            const dialog = document.createElement('dialog');
            dialog.style.minWidth = '60vw';
            dialog.open = true;
            dialog.id = 'profiler';

            tableString = '<table style="width: 100%"><tbody>';
            for (let i = 0; i < data.length -1; i++) {
                const entry = data[i];
                const duration = data[i+1] ? data[i+1]['timestamp'] - entry['timestamp'] : 0;
                tableString += '<tr><td>' + entry['parent'] + '</td><td>' + entry['message'] + '</td><td>' + duration.toPrecision(8) + '</td></tr>';
            }
            tableString += '<tr><td>' + data[data.length -1]['parent'] + '</td><td>' + data[data.length -1]['message'] + '</td><td>n/a</td></tr></tbody></table>';

            dialog.innerHTML = tableString;
            return dialog;
        };

        onDOMContentLoaded(() => {
            findComments(document).forEach((comment) => {
                if (comment.textContent === ' profiler-insert ') {
                    getEntries().then((entries) => {
                        entries.json().then((data) => {
                            const dialog = generateProfilerTable(data);

                            document.body.appendChild(dialog);
                        });
                    });
                }
            });
        });
        </script>
        JS;

        return $script;
    }
}
