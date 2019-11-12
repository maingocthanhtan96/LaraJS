const mix = require('laravel-mix');
const config = require('./webpack.config');
require('laravel-mix-eslint');

function publicPath(dir) {
  return path.join(__dirname, '/public', dir);
}

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
// mix.copy('node_modules/element-ui/lib/theme-chalk/fonts/*', 'public/css/fonts/');
mix.js('resources/js/app.js', 'public/js')
  .extract([
    'vue',
    'axios',
    'vuex',
    'vue-router',
    'vue-tables-2',
    'vue-i18n',
    'element-ui',
    'dropzone',
    'tui-editor',
    'codemirror',
    'moment',
    'lodash',
    'vue2-dropzone',
    'vuedraggable',
    'echarts',
    'vue-count-to',
  ])
  .options({
    processCssUrls: false,
    postCss: [
      require('tailwindcss')('./public/js/tailwind.config.js'),
      require('autoprefixer'),
      require('@fullhuman/postcss-purgecss')({
        // Specify the paths to all of the template files in your project
        content: [
          './resources/js/**/*.vue',
          './public/js/*.js',
        ],
        css: ['./resources/js/styles/*.scss', './public/css/*.css'],
        whitelist: ["html", "body", 'app'],
        whitelistPatterns: [/^el-/, /^fade-/, /^breadcrumb-/, /^vue-/, /^dropzone/, /^json/, /^larajs-/],
        whitelistPatternsChildren: [/^el-/, /^fade-/, /^breadcrumb-/, /^vue-/, /^dropzone/, /^json/, /^larajs-/],
        // Include any special characters you're using in this regular expression
        defaultExtractor: content => content.match(/[A-Za-z0-9-_:/]+/g) || []
      })
    ],
    clearConsole: true, // in watch mode, clears console after every build
  })
  .sass('resources/js/styles/index.scss', 'public/css/app.css', {
    implementation: require('node-sass'),
  });
mix.webpackConfig(config);

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


