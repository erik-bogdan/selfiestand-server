const colors = require('tailwindcss/colors')

module.exports = {
  content: [
    './vendor/ralphjsmit/tall-interactive/resources/views/**/*.blade.php',
    './resources/**/*.blade.php',
    './resources/views/**/*.blade.php',
    './vendor/filament/**/*.blade.php',
  ],
  darkMode: 'class',
  theme: {
    screens: {
      xsm: '480px',
      sm: '640px',
      md: '768px',
      lg: '1024px',
      xl: '1280px',
      '2xl': '1536px',
      '3xl': '1720px',
    },
    extend: {
      colors: {
        danger: colors.rose,
        primary: colors.blue,
        success: colors.green,
        warning: colors.yellow,
        'regal-blue': '#243c5a',
        'grey': '#D9D9D9'
      },
      opacity: {
        '50': '.50',
      }
    },
    container: {
      center: true,
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography'),
  ],
}