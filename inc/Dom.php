<?php
class DOM {

    public static function fonts(): void{
        $fonts = new GlobIterator(__DIR__."/../src/fonts/*.*");
        while($fonts->valid()){
            $font = $fonts->current();
            echo "<link rel='preload' href='/src/fonts/{$font->getFilename()}' as='font' type='font/{$font->getExtension()}' crossorigin>";
            $fonts->next();
        }
    }

    public static function component(string $component_name): void {
        $component_path = __DIR__.'/../src/components/';
        if (!strpos($component_name, '.php')) {
            $component_name .= '.php';
        }
        if(!file_exists($component_path.$component_name)) echo '404';
        include($component_path.$component_name);
    }

    public static function styles(): void {
        include(__DIR__.'/../styles.php');
    }

}
