const mix = require('laravel-mix');
const path = require('path');
require('laravel-mix-eslint-config');
require('laravel-mix-merge-manifest');
const BundleAnalyzerPlugin = require('webpack-bundle-analyzer').BundleAnalyzerPlugin;
const MomentLocalesPlugin = require('moment-locales-webpack-plugin');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
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
  })
);

mix
  .js('frontend/src/main.js', 'public/frontend/js/app.js')
  .webpackConfig({
    resolve: {
      extensions: ['.js', '.vue', '.json'],
      alias: {
        vue$: 'vue/dist/vue.esm.js',
        '@': path.join(__dirname, '/resources/js/'),
        '@fe': path.join(__dirname, '/frontend/src/'),
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
        {
          test: /(\.(png|jpe?g|gif|webp)$|^((?!font).)*\.svg$)/,
          loaders: {
            loader: 'file-loader',
            options: {
              name: 'images/[path][name].[ext]',
            },
          },
        },
      ],
    },
    output: {
      chunkFilename: mix.inProduction() ? 'frontend/js/chunks/[name].[chunkhash].js' : 'frontend/js/chunks/[name].js',
    },
    plugins: plugins,
  })
  .sass('frontend/src/styles/app.scss', 'public/frontend/css')
  .options({
    postCss: [require('autoprefixer')],
  })
  .extract(['vue', 'vuex', 'vue-router', 'axios', 'bootstrap', 'nprogress'])
  .eslint({
    enforce: 'pre',
    test: /\.(js|vue)$/, // will convert to /\.(js|vue)$/ or you can use /\.(js|vue)$/ by itself.
    exclude: /node_modules/, // will convert to regexp and work. or you can use a regular expression like /node_modules/,
    loader: 'eslint-loader',
    options: {
      fix: true,
      cache: false,
    },
  })
  .mergeManifest()
  .vue({ version: 2 });

if (isProduction) {
  mix.version();
} else {
  mix.sourceMaps().webpackConfig({
    devtool: 'eval-cheap-source-map', // Fastest for development
  });
}
