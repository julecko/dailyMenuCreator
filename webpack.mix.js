const mix = require('laravel-mix');
const BrowserSyncPlugin = require('browser-sync-webpack-plugin');

mix.js('resources/js/app.js', 'public/js')
    .js('resources/js/calendar.js', 'public/js')
    .copy('resources/assets', 'public/assets')
    .postCss('resources/css/app.css', 'public/css', [
        require('tailwindcss'),
        require('autoprefixer'),
        require('cssnano')({ preset: 'default' }),
    ])
    .sass('resources/scss/app.scss', 'public/css');

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
                notify: false,
                open: false,
                reloadDelay: 1000, // Optional: adjust for slower reloads
            })
        ],
    });
}
