const mix = require('laravel-mix');

mix.setPublicPath('./')
    .js('./src/js/index.js', 'js/index.js')
    .minify('js/index.js')
    .sass('./src/sass/style.sass', 'stylesheet/stylesheet.css')
    .version();

mix.setPublicPath('./')
    .styles(['./css/*.css', './stylesheet/stylesheet.css'], 'stylesheet/stylesheet.css')
    .minify('stylesheet/stylesheet.css')
    .version();
