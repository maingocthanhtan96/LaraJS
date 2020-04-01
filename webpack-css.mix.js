const mix = require('laravel-mix');
const mergeManifest = require('./mergeManifest');
const whitelister = require('purgecss-whitelister');

const purgecss = require('@fullhuman/postcss-purgecss')({
  // Specify the paths to all of the template files in your project
  content: ['./resources/js/**/*.{vue,js}'],
  whitelist: [
    'html',
    'body',
    'app',
    ...whitelister([
      './resources/js/styles/element-variables.scss',
      './resources/js/styles/transition.scss',
      './resources/js/styles/element-ui.scss',
    ]),
  ],
  // Include any special characters you're using in this regular expression
  defaultExtractor: content => content.match(/[\w-/:%]+(?<!:)/g) || [],
});

mix.extend('mergeManifest', mergeManifest);

mix
  .options({
    processCssUrls: false,
    postCss: [
      require('tailwindcss')('./public/js/tailwind.config.js'),
      require('autoprefixer'),
      mix.inProduction() ? purgecss : require('autoprefixer'),
    ],
    clearConsole: true, // in watch mode, clears console after every build
  })
  .sass('resources/js/styles/index.scss', 'public/css/app.css', {
    implementation: require('node-sass'),
  })
  .mergeManifest();

if (mix.inProduction()) {
  mix.version();
} else {
  mix.sourceMaps().webpackConfig({
    devtool: 'cheap-eval-source-map', // Fastest for development
  });
}
