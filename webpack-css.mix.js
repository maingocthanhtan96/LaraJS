const mix = require('laravel-mix');
const mergeManifest = require('./mergeManifest');
const whiteLister = require('purgecss-whitelister');
require('laravel-mix-purgecss');
const path = require('path');

function resolve(dir) {
  return path.join(__dirname, '/resources/js', dir);
}

mix.extend('mergeManifest', mergeManifest);

mix
  .options({
    processCssUrls: false,
    postCss: [
      require('tailwindcss')('./public/js/tailwind.config.js'),
      require('autoprefixer'),
    ],
    clearConsole: true, // in watch mode, clears console after every build
  })
  .sass('resources/js/styles/index.scss', 'public/css/app.css', {
    implementation: require('node-sass'),
  })
  .purgeCss({
    whitelist: [
      ...whiteLister([
        resolve('/styles/element-variables.scss'),
        resolve('/styles/element-ui.scss'),
        resolve('/styles/sidebar.scss'),
      ]),
    ],
  })
  .mergeManifest();

if (mix.inProduction()) {
  mix.version();
} else {
  mix.sourceMaps().webpackConfig({
    devtool: 'cheap-eval-source-map', // Fastest for development
  });
}
