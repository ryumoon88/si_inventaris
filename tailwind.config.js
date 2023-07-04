import colors from 'tailwindcss/colors'
import forms from '@tailwindcss/forms'
import typography from '@tailwindcss/typography'
import defaultTheme from 'tailwindcss/defaultTheme'

/** @type {import('tailwindcss').Config} */
export default {
    mode: 'jit',
    content: [
        './resources/views/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ],
    theme: {
        extend: {
            // colors: {
            //     danger: colors.rose,
            //     primary: colors.blue,
            //     success: colors.green,
            //     warning: colors.yellow,
            // },

            colors: {
                danger: colors.rose,
                primary: colors.amber,
                success: colors.green,
                warning: colors.amber,
            },
            fontFamily: {
                sans: ['DM Sans', ...defaultTheme.fontFamily.sans],
            },
        },
    },
    darkMode: 'class',
    // safelist: [
    //     {
    //         pattern: /./
    //     }
    // ],
    plugins: [
        forms,
        typography,
    ],
}

