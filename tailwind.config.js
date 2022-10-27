/** @type {import('tailwindcss').Config} */
module.exports = {
    darkMode: 'class',

    content: [
        './resources/js/**/*.vue',
        './resources/views/emails/*'
    ],

    theme: {
        extend: {
            colors: {
                // Remain Consistent with darker colors
                // darker is black, for rest add 'd_' + color name
                // calculate colors by setting one dark color as '50'
                // then add to it 4% in hsv scheme, do that until 900
                darker: {
                    // '50':  "#000000", // black to
                    '50':  "#0a0a0a",
                    '100': "#141414",
                    '200': "#1e1e1e",
                    '300': "#282828",
                    '400': "#3c3c3c",
                    '500': "#464646",
                    '600': "#505050",
                    '700': "#5a5a5a",
                    '800': "#646464",
                    '900': "#6e6e6e",
                },
                d_orange: {
                    '50':  "#240c00", // from ||  hsv (20°, 100%, 14%)
                    '100': "#2e0f00",
                    '200': "#381300",
                    '300': "#421600",
                    '400': "#4d1900",
                    '500': "#571c00",
                    '600': "#611f00",
                    '700': "#6b2200",
                    '800': "#7a2700",
                    '900': "#852a00", // to ||  hsv (19°, 100%, 52%)
                }
            },

        },
    },
    plugins: [],
}
