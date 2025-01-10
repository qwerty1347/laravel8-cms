const mix = require('laravel-mix');

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

mix.js('resources/js/app.js', 'public/js')
   .postCss('resources/css/app.css', 'public/css', [
       require('tailwindcss'),
       require('autoprefixer'),
   ])
   .sass('resources/coreui/src/scss/style.scss', 'public/css')
   .sass('resources/coreui/src/scss/examples.scss', 'public/css')
   .sass('resources/coreui/src/scss/vendors/simplebar.scss', 'public/css/vendors')
   .copy('node_modules/simplebar/dist/simplebar.css', 'public/css/simplebar.css')
   .copy('node_modules/@coreui/chartjs/dist/css/coreui-chartjs.css', 'public/css/coreui-chartjs.css')
   .copy('node_modules/simplebar/dist/simplebar.min.js', 'public/js/simplebar.min.js')
   .copy('node_modules/@coreui/chartjs/dist/js/coreui-chartjs.js', 'public/js/coreui-chartjs.js')
   .copy('node_modules/@coreui/coreui/dist/js/coreui.bundle.min.js', 'public/js/coreui.bundle.min.js')
   .copy('node_modules/@coreui/utils/dist/umd/index.js', 'public/js/coreui-utils.js');
