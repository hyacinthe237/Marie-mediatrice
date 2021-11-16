let mix = require('laravel-mix');
require('laravel-mix-merge-manifest');

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

 mix.webpackConfig({
    resolve: {
        alias: {
            '@': path.resolve(__dirname, './resources/assets/js')
        }
    }
 });

 mix.autoload({
     jquery: ['$', 'window.jQuery', 'jQuery', 'window.$', 'jquery', 'window.jquery']
 });

 mix.js('resources/assets/js/app.js', 'public/assets/js')
 .extract(['vue', 'jquery', 'bootstrap-sass', 'lodash', 'moment', 'sweetalert2',
     'toastr', 'vuex', 'axios', 'moment-range', 'vee-validate', 'vue2-dropzone',
     'bootstrap-datepicker'
 ]);

 mix.sass('resources/assets/sass/app.scss', 'public/assets/css/app.min.css')
 .options({
     processCssUrls: false,
     imgLoaderOptions: {
         enabled: false
     },
 })
 .mergeManifest();

 if (mix.inProduction()) {
     mix.version();
 }
