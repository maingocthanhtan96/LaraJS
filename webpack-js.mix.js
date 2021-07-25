const mix = require('laravel-mix');
const config = require('./webpack.config');
const path = require('path');
const isProduction = mix.inProduction();
require('laravel-mix-eslint-config');
require('laravel-mix-merge-manifest');

function resolve(dir) {
  return path.join(__dirname, '/resources/js', dir);
}

Mix.listen('configReady', webpackConfig => {
  // Add "svg" to image loader test
  const imageLoaderConfig = webpackConfig.module.rules.find(
    rule => String(rule.test) === String(/(\.(png|jpe?g|gif|webp)$|^((?!font).)*\.svg$)/)
  );
  imageLoaderConfig.exclude = resolve('icons');
});

mix
  .js('resources/js/app.js', 'public/js')
  .extract(['vue', 'vuex', 'vue-router', 'vue-i18n', 'axios', 'element-ui', 'nprogress'])
  .webpackConfig(config)
  .mergeManifest()
  .eslint({
    enforce: 'pre',
    test: /\.(js|vue)$/, // will convert to /\.(js|vue)$/ or you can use /\.(js|vue)$/ by itself.
    exclude: /node_modules/, // will convert to regexp and work. or you can use a regular expression like /node_modules/,
    loader: 'eslint-loader',
    options: {
      fix: !isProduction,
      cache: false,
    },
  })
  .vue({ version: 2 });

if (isProduction) {
  mix.version();
} else {
  // Development settings
  // mix.browserSync({
  //   proxy: process.env.APP_URL,
  //   files: ['resources/js/**/*'],
  // });
  mix.sourceMaps().webpackConfig({
    devtool: 'eval-cheap-source-map', // Fastest for development
  });
}
