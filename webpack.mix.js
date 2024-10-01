const mix = require('laravel-mix');
const BrowserSyncPlugin = require('browser-sync-webpack-plugin');

mix.js('resources/js/app.js', 'public/js')
    .postCss('resources/css/app.css', 'public/css', [
        require('tailwindcss'),
        require('autoprefixer'),
        require('cssnano')({ preset: 'default' }),
    ])
    .sass('resources/sass/app.scss', 'public/css');

if (mix.inProduction()) {
    mix.version();
} else {
    mix.webpackConfig({
        plugins: [
            new BrowserSyncPlugin({
                proxy: 'http://localhost:8000', // Change to your local dev URL
                files: [
                    'app/**/*.php',
                    'resources/views/**/*.blade.php',
                    'public/js/**/*.js',
                    'public/css/**/*.css',
                ],
                reloadDelay: 1000, // Optional: adjust for slower reloads
            })
        ],
    });
}
