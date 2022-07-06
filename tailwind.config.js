const colors = require('tailwindcss/colors')

module.exports = {
  content: [
    './vendor/ralphjsmit/tall-interactive/resources/views/**/*.blade.php',
    './resources/**/*.blade.php',
    './vendor/filament/**/*.blade.php',
  ],
  darkMode: 'class',
  theme: {
    extend: {
      colors: {
        danger: colors.rose,
        primary: colors.blue,
        success: colors.green,
        warning: colors.yellow,
        'regal-blue': '#243c5a',
      },
      opacity: {
        '50': '.50',
      }
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography'),
  ],
}