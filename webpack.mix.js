const mix = require('laravel-mix');
const path = require('path');
const fs = require('fs-extra');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

// Copy public assets from resources to public directory
mix.copyDirectory('resources/assets_public', 'public/assets_public');

mix.js('resources/js/app.js', 'public/js')
    .postCss('resources/css/app.css', 'public/css', [
        //
    ]).minify('public/assets/js/soft-ui-dashboard.js');
mix.sass('public/assets/scss/soft-ui-dashboard.scss', 'public/assets/css');
