const mix = require('laravel-mix');

mix.js('./src/js/index.js', './js/index.js');
mix.sass('./src/sass/style.sass', './stylesheet/stylesheet.css');
mix.styles(['./css/*.css', './stylesheet/stylesheet.css'], './stylesheet/stylesheet.css')