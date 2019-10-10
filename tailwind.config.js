module.exports = {
  theme: {
    extend: {

    colors: {
      'exo-blue': {
        100: '#EBF8FE',
        200: '#CEEEFC',
        300: '#B0E4FA',
        400: '#74D0F6',
        500: '#39BCF2',
        600: '#33A9DA',
        700: '#227191',
        800: '#1A556D',
        900: '#113849',
        },
    },
    }
  },
  variants: {},
  plugins: [
      require('@tailwindcss/custom-forms'),
      require('tailwindcss-plugins/pagination')
  ]
}
