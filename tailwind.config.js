/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './resources/views/**/*.blade.php', // Include Blade templates
        './resources/js/**/*.svelte',        // Include Svelte components
        './resources/js/**/*.js', // Include JS files],
    ],
    theme: {
        extend: {},
    },
    plugins: [],
}
