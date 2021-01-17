const mix = require('laravel-mix');
const config = require('./webpack.config');
const mergeManifest = require('./mergeManifest');
const path = require('path');
require('laravel-mix-eslint');

function resolve(dir) {
  return path.join(__dirname, '/resources/js', dir);
}

Mix.listen('configReady', webpackConfig => {
  // Add "svg" to image loader test
  const imageLoaderConfig = webpackConfig.module.rules.find(
    rule =>
      String(rule.test) ===
      String(/(\.(png|jpe?g|gif|webp)$|^((?!font).)*\.svg$)/)
  );
  imageLoaderConfig.exclude = resolve('icons');
});

mix
  .extend('mergeManifest', mergeManifest)
  .js('resources/js/app.js', 'public/js')
  .extract([
    'vue',
    'vuex',
    'vue-router',
    'vue-i18n',
    'axios',
    'element-ui',
    'nprogress',
    'echarts',
  ])
  .webpackConfig(config)
  .disableNotifications()
  .mergeManifest();

if (mix.inProduction()) {
  mix.version();
} else {
  if (process.env.LARAJS_USE_ESLINT === 'true') {
    mix.eslint({
      fix: true,
      cache: false,
    });
  }
  // Development settings
  // mix.browserSync({
  //   proxy: process.env.APP_URL,
  //   files: ['resources/js/**/*'],
  // });
  mix.sourceMaps().webpackConfig({
    devtool: 'cheap-eval-source-map', // Fastest for development
  });
}
