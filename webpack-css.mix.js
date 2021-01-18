const mix = require('laravel-mix');
const path = require('path');
const tailwindcss = require('tailwindcss');
const whiteLister = require('purgecss-whitelister');
require('laravel-mix-purgecss');
require('laravel-mix-merge-manifest');

function resolve(dir) {
  return path.join(__dirname, '/resources/js', dir);
}

mix
  .sass('resources/js/styles/app.scss', 'public/css', {
    implementation: require('node-sass'),
  })
  .options({
    processCssUrls: false,
    postCss: [
      tailwindcss('public/js/tailwind.config.js'),
      require('autoprefixer'),
    ],
    autoprefixer: { remove: false },
    clearConsole: true, // in watch mode, clears console after every build
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
    devtool: 'eval-cheap-source-map', // Fastest for development
  });
}
