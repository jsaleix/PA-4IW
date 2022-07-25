/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './templates/**/*.html.twig',
    './templates/front/defaut/*.html.twig',
    './templates/**/**/*.html.twig',
    './templates/*.html.twig',
    './assets/js/**/*.js',
    './assets/js/**/*.jsx', // Si vous utilisez des fichiers React JSX
    ], 
  theme: {
    extend: {},
  },
  plugins: [
    require('@tailwindcss/forms'),
    require("daisyui"),
    require('@tailwindcss/aspect-ratio')
  ],
};
