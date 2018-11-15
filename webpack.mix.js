const mix = require('laravel-mix')

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

// mix.js('resources/js/app.js', 'public/js')
//    .sass('resources/sass/app.scss', 'public/css');

mix.combine(['resources/css/materialize.min.css', 'resources/css/style.css'], 'public/css/app.css')

mix.combine(['resources/js/packages/*'], 'public/js/packages.js')
mix.combine(['resources/js/dt-custom.js', 'resources/js/script.js'], 'public/js/app.js')
mix.combine(['resources/js/data.js'], 'public/js/data.js')
mix.combine(['resources/js/account.js'], 'public/js/account.js')
