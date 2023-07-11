<?php
class Font {
    public static function get_fonts(){
        $fonts = new GlobIterator(__DIR__."/../src/fonts/*.*");
        while($fonts->valid()){
            $font = $fonts->current();
            echo "<link rel='preload' href='/src/fonts/{$font->getFilename()}' as='font' type='font/{$font->getExtension()}' crossorigin>";
            $fonts->next();
        }
    }
}
