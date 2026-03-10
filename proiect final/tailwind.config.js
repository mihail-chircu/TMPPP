import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {
            fontFamily: {
                display: ['"Baloo 2"', ...defaultTheme.fontFamily.sans],
                body: ['Nunito', ...defaultTheme.fontFamily.sans],
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                /* Primary brand — roz */
                kinder: {
                    50:  '#FFF0F3',
                    100: '#FFE0E8',
                    200: '#FFC1D1',
                    300: '#FF94AE',
                    400: '#FF6089',
                    500: '#F83B65',
                    600: '#E5184E',
                    700: '#C20D40',
                    800: '#A10E3A',
                    900: '#8A1038',
                },
                /* Warm neutral */
                'kinder-brown': {
                    50:  '#FEFCFA',
                    100: '#F7F4F0',
                    200: '#EDE8E2',
                    300: '#D9D2C9',
                    400: '#B5AC9D',
                    500: '#9A8F7F',
                    600: '#7D7164',
                    700: '#665C51',
                    800: '#2D2926',
                    900: '#1A1715',
                },
                /* Accent palette */
                candy: {
                    pink:   '#F83B65',
                    mint:   '#2DD4A8',
                    green:  '#43B854',
                    yellow: '#FBBF24',
                    purple: '#8B5CF6',
                    orange: '#FF6B35',
                    teal:   '#14B8A6',
                    red:    '#EF4444',
                },
            },
            borderRadius: {
                '4xl': '2rem',
                '5xl': '2.5rem',
            },
            spacing: {
                '18': '4.5rem',
                '22': '5.5rem',
                '26': '6.5rem',
                '30': '7.5rem',
            },
            boxShadow: {
                'soft':    '0 2px 8px -2px rgba(0,0,0,0.08)',
                'soft-md': '0 4px 16px -4px rgba(0,0,0,0.1)',
                'soft-lg': '0 8px 32px -8px rgba(0,0,0,0.12)',
                'soft-xl': '0 16px 48px -12px rgba(0,0,0,0.16)',
                'glow':    '0 4px 24px -4px rgba(248,59,101,0.3)',
                'glow-lg': '0 8px 32px -4px rgba(248,59,101,0.35)',
                'glow-mint': '0 4px 24px -4px rgba(45,212,168,0.3)',
            },
            fontSize: {
                '2xs': ['0.625rem', { lineHeight: '0.875rem' }],
            },
            animation: {
                'float':        'float 6s ease-in-out infinite',
                'float-delay':  'float 6s ease-in-out 2s infinite',
                'float-slow':   'float 8s ease-in-out 1s infinite',
                'wiggle':       'wiggle 3s ease-in-out infinite',
                'fade-in':      'fadeIn 0.3s ease-out',
                'slide-up':     'slideUp 0.4s ease-out',
                'fade-in-up':   'fadeInUp 0.7s ease-out both',
                'scale-in':     'scaleIn 0.5s ease-out both',
                'bounce-sm':    'bounceSm 0.4s ease-out',
                'shimmer':      'shimmer 2s linear infinite',
            },
            keyframes: {
                float: {
                    '0%, 100%': { transform: 'translateY(0px)' },
                    '50%':      { transform: 'translateY(-12px)' },
                },
                wiggle: {
                    '0%, 100%': { transform: 'rotate(-3deg)' },
                    '50%':      { transform: 'rotate(3deg)' },
                },
                fadeIn: {
                    '0%':   { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                slideUp: {
                    '0%':   { opacity: '0', transform: 'translateY(12px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                fadeInUp: {
                    '0%':   { opacity: '0', transform: 'translateY(30px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                scaleIn: {
                    '0%':   { opacity: '0', transform: 'scale(0.95)' },
                    '100%': { opacity: '1', transform: 'scale(1)' },
                },
                bounceSm: {
                    '0%':   { transform: 'scale(1)' },
                    '50%':  { transform: 'scale(1.25)' },
                    '100%': { transform: 'scale(1)' },
                },
                shimmer: {
                    '0%':   { backgroundPosition: '-200% 0' },
                    '100%': { backgroundPosition: '200% 0' },
                },
            },
        },
    },
    plugins: [],
};
