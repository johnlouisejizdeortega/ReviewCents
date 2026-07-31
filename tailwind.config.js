import defaultTheme from 'tailwindcss/defaultTheme';
import colors from 'tailwindcss/colors';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';
import animate from 'tailwindcss-animate';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './vendor/mallardduck/blade-lucide-icons/resources/svg/*.svg',
    ],

    theme: {
        extend: {
            colors: {
                // Cooler, more modern neutral ramp — all existing gray-* utilities resolve to zinc.
                gray: colors.zinc,
            },
            fontFamily: {
                sans: ['"Inter Variable"', ...defaultTheme.fontFamily.sans],
                mono: ['"JetBrains Mono Variable"', ...defaultTheme.fontFamily.mono],
            },
            letterSpacing: {
                tightish: '-0.02em',
            },
            borderRadius: {
                '2xl': '1rem',
                '3xl': '1.25rem',
            },
            boxShadow: {
                soft: '0 1px 2px 0 rgb(0 0 0 / 0.04), 0 1px 3px 0 rgb(0 0 0 / 0.06)',
            },
            keyframes: {
                blink: {
                    '0%, 100%': { opacity: '1' },
                    '50%': { opacity: '0' },
                },
                reveal: {
                    from: { opacity: '0', transform: 'translateY(12px)' },
                    to: { opacity: '1', transform: 'translateY(0)' },
                },
            },
            animation: {
                blink: 'blink 1s step-end infinite',
                reveal: 'reveal 0.6s cubic-bezier(0.22, 1, 0.36, 1) both',
            },
            typography: ({ theme }) => ({
                zinc: {
                    css: {
                        '--tw-prose-body': theme('colors.zinc[600]'),
                        '--tw-prose-headings': theme('colors.zinc[900]'),
                    },
                },
            }),
        },
    },

    plugins: [forms, typography, animate],
};
