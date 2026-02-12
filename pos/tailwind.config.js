/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./index.html",
        "./src/**/*.{js,ts,jsx,tsx}",
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Outfit', 'sans-serif'],
            },
            colors: {
                primary: '#6200EE', // Deep Purple
                secondary: '#03DAC6', // Teal
                background: '#F9FAFB', // Light Gray
                surface: '#ffffff', // White
            },
        },
    },
    plugins: [],
}
