const path = require('path');
const ChunkRenamePlugin = require('webpack-chunk-rename-plugin');
const mix = require('laravel-mix');
const BundleAnalyzerPlugin = require('webpack-bundle-analyzer')
  .BundleAnalyzerPlugin;

function resolve(dir) {
  return path.join(__dirname, '/resources/js', dir);
}

const rawArgv = process.argv.slice(2);
const report = rawArgv.includes('--report');
const plugins = [];
if (report) {
  plugins.push(
    new BundleAnalyzerPlugin({
      openAnalyzer: true
    })
  );
}
plugins.push(
  new ChunkRenamePlugin({
    initialChunksWithEntry: true,
    '/js/vendor': '/js/vendor.js'
  })
);

module.exports = {
  resolve: {
    extensions: ['.js', '.vue', '.json'],
    alias: {
      vue$: 'vue/dist/vue.esm.js',
      '@': path.join(__dirname, '/resources/js')
    }
  },
  module: {
    rules: [
      {
        test: /\.svg$/,
        loader: 'svg-sprite-loader',
        include: [resolve('icons')],
        options: {
          symbolId: 'icon-[name]'
        }
      },
      {
        test: /\.(js)$/,
        use: 'babel-loader',
        exclude: /node_modules/
      }
    ]
  },
  output: {
    chunkFilename: mix.inProduction()
      ? 'js/chunks/[name].[chunkhash].js'
      : 'js/chunks/[name].js'
  },
  plugins: plugins
};
