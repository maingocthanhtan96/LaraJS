const path = require('path');
const ChunkRenamePlugin = require('webpack-chunk-rename-plugin');
const mix = require('laravel-mix');
const BundleAnalyzerPlugin = require('webpack-bundle-analyzer')
  .BundleAnalyzerPlugin;
const MomentLocalesPlugin = require('moment-locales-webpack-plugin');
const WebpackBuildNotifierPlugin = require('webpack-build-notifier');

function resolve(dir) {
  return path.join(__dirname, '/resources/js', dir);
}

const rawArgv = process.argv.slice(2);
const report = rawArgv.includes('--report');
const plugins = [];
if (report) {
  plugins.push(
    new BundleAnalyzerPlugin({
      openAnalyzer: true,
    })
  );
}
plugins.push(
  new ChunkRenamePlugin({
    initialChunksWithEntry: true,
    '/js/vendor': '/js/vendor.js',
  }),
  // ('en': default)
  new MomentLocalesPlugin({
    localesToKeep: ['ja'],
  }),
  // ('en': default)
  new WebpackBuildNotifierPlugin({
    title: 'LaraJS',
    logo: path.resolve('./public/images/logo-tanmnt.png'),
    // - false: show notification build
    // - true : show notification fail or first success
    // - always: show only notification success
    // - initial: like build always
    suppressSuccess: true, // don't spam success notifications
  })
);

module.exports = {
  resolve: {
    extensions: ['.js', '.vue', '.json'],
    alias: {
      vue$: 'vue/dist/vue.esm.js',
      '@': path.join(__dirname, '/resources/js'),
    },
    fallback: {
      path: require.resolve('path-browserify'),
    },
  },
  module: {
    rules: [
      {
        test: /\.svg$/,
        loader: 'svg-sprite-loader',
        include: [resolve('icons')],
        options: {
          symbolId: 'icon-[name]',
        },
      },
      {
        test: /\.(js)$/,
        use: 'babel-loader',
        exclude: /node_modules/,
      },
      {
        test: /\.vue$/,
        loader: 'vue-loader',
      },
    ],
  },
  output: {
    chunkFilename: mix.inProduction()
      ? 'js/chunks/[name].[chunkhash].js'
      : 'js/chunks/[name].js',
  },
  plugins: plugins,
  optimization: {
    providedExports: false,
    sideEffects: false,
    usedExports: false,
  },
};
