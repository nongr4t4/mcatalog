import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'ui-bg': '#121212',
                'ui-fg': '#E0E0E0',
                'ui-muted': '#B0B0B0',
                'ui-panel': '#444444',
                'ui-border': '#888888',
                'ui-accent': '#00FF85',
                'ui-accent2': '#FF0099',
            },
        },
    },

    plugins: [forms],
};
