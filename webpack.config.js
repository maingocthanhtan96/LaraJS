const path = require('path');
const ChunkRenamePlugin = require('webpack-chunk-rename-plugin');
const mix = require('laravel-mix');
const BundleAnalyzerPlugin = require('webpack-bundle-analyzer').BundleAnalyzerPlugin;
const MomentLocalesPlugin = require('moment-locales-webpack-plugin');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');

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
  new CleanWebpackPlugin({
    cleanOnceBeforeBuildPatterns: [path.resolve(__dirname, 'public/js/chunks/**/*')],
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
      // {
      //   test: /\.scss$/,
      //   use: [
      //     {
      //       loader: 'style-loader', // creates style nodes from JS strings
      //     },
      //     {
      //       loader: 'css-loader', // translates CSS into CommonJS
      //     },
      //     {
      //       loader: 'sass-loader', // compiles Sass to CSS
      //     },
      //   ],
      // },
    ],
  },
  output: {
    chunkFilename: mix.inProduction() ? 'js/chunks/[name].[chunkhash].js' : 'js/chunks/[name].js',
  },
  plugins: plugins,
  optimization: {
    providedExports: false,
    sideEffects: false,
    usedExports: false,
  },
};
