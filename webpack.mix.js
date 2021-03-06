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

mix.js('resources/assets/panel/js/app.js', 'public/assets/panel/js').vue({ version: 2 })
    .sass('resources/assets/panel/sass/app.scss', 'public/assets/panel/css');

mix.webpackConfig(webpack => {
    return {
        plugins: [
            new webpack.ProvidePlugin({
                'window.Quill': 'quill',
                'Quill': 'quill'
            })
        ]
    };
});

mix.webpackConfig({
    devServer: {
        port: '8080'
    },
});

if (mix.inProduction()) {
    mix.version();
}
