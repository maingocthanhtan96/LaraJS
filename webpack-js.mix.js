const mix = require('laravel-mix');
const config = require('./webpack.config');
const ChunkRenamePlugin = require('webpack-chunk-rename-plugin');
const mergeManifest = require('./mergeManifest');
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

mix.extend('mergeManifest', mergeManifest);
mix.webpackConfig({
    output: {
      chunkFilename: mix.inProduction() ? 'js/chunks/[name].[chunkhash].js' : 'js/chunks/[name].js'
    },
    plugins: [
      new ChunkRenamePlugin({
        initialChunksWithEntry: true,
        '/js/vendor': '/js/vendor.js'
      }),
    ],
  })
  .js('resources/js/app.js', 'public/js')
  .extract([
    'vue',
    'vuex',
    'vue-router',
    'vue-i18n',
    'axios',
    'element-ui',
    'vue-tables-2',
  ])
  .webpackConfig(config)
  .mergeManifest();

if (mix.inProduction()) {
  mix.version();
} else {
  if (process.env.LARAVUE_USE_ESLINT === 'true') {
    mix.eslint({
      fix: true,
      cache: false,
    });
  }
  // Development settings
  // mix.browserSync({
  //   proxy: process.env.APP_URL,
  //   files: ['resources/js/**/*']
  // });
  mix
    .sourceMaps()
    .webpackConfig({
      devtool: 'cheap-eval-source-map', // Fastest for development
    });
}
