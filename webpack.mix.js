const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix
    .js('resources/mobile_number/js/intlTelInput.js', 'public/mobile_number/js')
    .copy('resources/images', 'public/images')
    .copy('resources/fonts', 'public/fonts')
    .copy('resources/mobile_number', 'public/mobile_number')
    .sourceMaps();
