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

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css');

mix.extract();

<<<<<<< HEAD
// mix.browserSync('localhost:8000');
=======
mix.browserSync('localhost:8000');
>>>>>>> 66daa276ed365879a5787ca2776ab9cb49b86504
