const mix = require('laravel-mix');
const path = require('path');
require('laravel-mix-merge-manifest');
const BundleAnalyzerPlugin = require('webpack-bundle-analyzer')
  .BundleAnalyzerPlugin;
const MomentLocalesPlugin = require('moment-locales-webpack-plugin');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');

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
        '@': path.resolve(__dirname, 'frontend/src/'),
        '@themeConfig': path.resolve(__dirname, 'frontend/themeConfig.js'),
        '@core': path.resolve(__dirname, 'frontend/src/@core'),
        '@validations': path.resolve(
          __dirname,
          'frontend/src/@core/utils/validations/validations.js'
        ),
        '@axios': path.resolve(__dirname, 'frontend/src/libs/axios'),
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
                  includePaths: [
                    'frontend/node_modules',
                    'frontend/src/assets',
                  ],
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
      chunkFilename: mix.inProduction()
        ? 'frontend/js/chunks/[name].[chunkhash].js'
        : 'frontend/js/chunks/[name].js',
    },
    plugins: plugins,
  })
  .sass('resources/sass/app.scss', 'public/frontend/css')
  .options({
    postCss: [require('autoprefixer'), require('postcss-rtl')],
  })
  .mergeManifest()
  .vue({ version: 2 });

if (mix.inProduction()) {
  mix.version();
} else {
  mix.sourceMaps().webpackConfig({
    devtool: 'eval-cheap-source-map', // Fastest for development
  });
}
