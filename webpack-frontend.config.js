const path = require('path');
const mix = require('laravel-mix');
const MomentLocalesPlugin = require('moment-locales-webpack-plugin');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const ESLintPlugin = require('eslint-webpack-plugin');
const BundleAnalyzerPlugin = require('webpack-bundle-analyzer').BundleAnalyzerPlugin;

const isProduction = mix.inProduction();
const plugins = [];
const rawArgv = process.argv.slice(2);
const report = rawArgv.includes('--report');

if (report) {
  plugins.push(
    new BundleAnalyzerPlugin({
      openAnalyzer: true,
    })
  );
}
plugins.push(
  // ('en': default)
  new MomentLocalesPlugin({
    localesToKeep: ['ja'],
  }),
  new CleanWebpackPlugin({
    cleanOnceBeforeBuildPatterns: [path.resolve(__dirname, 'public/frontend/js/chunks/**/*')],
  }),
  new ESLintPlugin({
    extensions: ['js', 'vue'],
    fix: !isProduction,
  })
);
module.exports = {
  resolve: {
    extensions: ['.js', '.vue', '.json'],
    alias: {
      Vue: 'vue/dist/vue.esm-bundler.js',
      '@': path.join(__dirname, '/resources/js/'),
      '@fe': path.join(__dirname, '/frontend/src/'),
    },
    fallback: {
      path: require.resolve('path-browserify'),
    },
  },
  module: {
    rules: [
      {
        test: /\.s[ac]ss$/i,
        use: [
          {
            loader: 'sass-loader',
            options: {
              sassOptions: {
                includePaths: ['frontend/node_modules', 'frontend/src/assets', 'frontend/src/styles'],
              },
            },
          },
        ],
      },
    ],
  },
  output: {
    chunkFilename: isProduction ? 'frontend/js/chunks/[name].[chunkhash].js' : 'frontend/js/chunks/[name].js',
  },
  plugins: plugins,
  optimization: {
    providedExports: false,
    sideEffects: false,
    usedExports: false,
  },
};
