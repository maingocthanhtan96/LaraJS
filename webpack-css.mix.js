const mix = require('laravel-mix');
require('laravel-mix-purgecss');
require('laravel-mix-merge-manifest');

mix
  .sass('resources/js/styles/app.scss', 'public/css', {
    implementation: require('node-sass'),
  })
  .options({
    processCssUrls: false,
    postCss: [require('tailwindcss'), require('autoprefixer')],
    autoprefixer: { remove: false },
    clearConsole: true, // in watch mode, clears console after every build
  })
  .purgeCss({
    safelist: {
      standard: [/-active$/, /-enter$/, /-leave-to$/, /show$/, /^el-/],
    },
  })
  .mergeManifest();

if (mix.inProduction()) {
  mix.version();
} else {
  mix.sourceMaps().webpackConfig({
    devtool: 'eval-cheap-source-map', // Fastest for development
  });
}
