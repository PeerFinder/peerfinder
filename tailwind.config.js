module.exports = {
  mode: 'jit',
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
    './modules/**/resources/**/*.blade.php',
    './modules/**/resources/**/*.js.php',
    './modules/**/resources/**/*.vue.php',
    './public/content/**/*.html',
  ],
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
      'sans': ['Quicksand', 'sans-serif'],
      'serif': ['RobotoSlab', 'serif'],
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography'),
    require('@tailwindcss/aspect-ratio'),
    require('@tailwindcss/line-clamp'),
  ]
}