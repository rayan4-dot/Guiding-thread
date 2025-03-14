/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {
            colors: {
                'primary': '#1e40af',
                'primary-focus': '#1e3a8a',
                'primary-content': '#ffffff',
                'secondary': '#3b82f6',
                'accent': '#0ea5e9',
                'neutral': '#1f2937',
                'base-100': '#0f172a',
                'base-200': '#1e293b',
                'base-300': '#334155',
                'info': '#38bdf8',
                'success': '#4ade80',
                'warning': '#facc15',
                'error': '#f87171',
            }
        }
    },
    plugins: [require('daisyui')],
    daisyui: {
        themes: [
            {
                dark: {
                    'primary': '#1e40af',
                    'primary-focus': '#1e3a8a',
                    'primary-content': '#ffffff',
                    'secondary': '#3b82f6',
                    'accent': '#0ea5e9',
                    'neutral': '#1f2937',
                    'base-100': '#0f172a',
                    'base-200': '#1e293b',
                    'base-300': '#334155',
                    'info': '#38bdf8',
                    'success': '#4ade80',
                    'warning': '#facc15',
                    'error': '#f87171',
                },
            },
        ],
    },
};