let tailwindcss = require("tailwindcss")

module.exports = {
    plugins: [
      require("tailwindcss"),
      require('postcss-import'),
      require('autoprefixer'),
      require('@tailwindcss/forms'),
    ]
};