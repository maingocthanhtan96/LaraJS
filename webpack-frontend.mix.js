const mix = require('laravel-mix');
const config = require('./webpack-frontend.config');
require('laravel-mix-merge-manifest');
require('laravel-mix-purgecss');
const isProduction = mix.inProduction();

mix
  .js('frontend/src/app.js', 'public/frontend/js/app.js')
  .webpackConfig(config)
  .sass('frontend/src/styles/app.scss', 'public/frontend/css')
  .options({
    postCss: [require('autoprefixer')],
  })
  .extract(['vue', 'vuex', 'vue-router', 'axios', 'bootstrap', 'nprogress'])
  .purgeCss()
  .mergeManifest()
  .vue({ version: 2 });

if (isProduction) {
  mix.version();
} else {
  mix.sourceMaps().webpackConfig({
    devtool: 'eval-cheap-source-map', // Fastest for development
  });
}
