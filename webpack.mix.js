const mix = require('laravel-mix');

mix
    .js('src/js/main.js', 'dist/main.js')
    .styles('src/styles/app.css', 'dist/main.css')
    .minify('dist/main.js')
    .minify('dist/main.css')
    .options({
        processCssUrls: false,
    });

