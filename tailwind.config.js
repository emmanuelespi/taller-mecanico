import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                base: '#0F1117',
                card: '#1A1D27',
                hover: '#21253A',
                border: '#2D3148',
                accent:{
                    DEFAULT: '#F97316',
                    dim: 'rgba(249,115,22,0.12)',
                },
            },
            fontFamily: {
                rajdhani: ['Rajdhani', 'sans-serif'],
                dm: ['DM Sans', 'sans-serif'],
            },
        },
    },

    plugins: [forms],
};
