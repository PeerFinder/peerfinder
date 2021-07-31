module.exports = {
  mode: 'jit',
  purge: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
  ],
  darkMode: 'media', // or 'media' or 'class'
  theme: {
    extend: {
      colors: {
        'pf-lightblue': '#86BBD8',
        'pf-midblue': '#33658A',
        'pf-darkblue': '#2E4858',
        'pf-darkorange': '#F26419',
        'pf-midorange': '#F6AE2D',
        'pf-lightorange': '#FCE5C3',
      },
      backgroundImage: theme => ({

      }),
    },
    fontFamily: {
      'sans': ['Quicksilver', 'sans-serif'],
    },
  },
  variants: {
    extend: {},
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography'),
    require('@tailwindcss/aspect-ratio'),
  ]
}