const mix = require('laravel-mix');
const mergeManifest = require('./mergeManifest');
const whitelister = require('purgecss-whitelister');
require('laravel-mix-eslint');

const purgecss = require('@fullhuman/postcss-purgecss')({
  // Specify the paths to all of the template files in your project
  content: [
    './resources/js/**/*.{vue,js}',
  ],
  whitelist: [
    'html',
    'body',
    'app',
    ...whitelister([
      'node_modules/element-ui/lib/theme-chalk/index.css',
    ]),
  ],
  // Include any special characters you're using in this regular expression
  defaultExtractor: content => content.match(/[\w-/:]+(?<!:)/g) || [],
});

mix.extend('mergeManifest', mergeManifest);

mix.webpackConfig({
  output: {
    chunkFilename: 'js/chunks/[name].js',
  },
  resolve: {
    alias: {
      '@': path.join(__dirname, '/resources/js'),
    },
  },
});

mix.options({
  processCssUrls: false,
  postCss: [
    require('tailwindcss')('./public/js/tailwind.config.js'),
    require('autoprefixer'),
    mix.inProduction() ? purgecss : require('autoprefixer'),
  ],
  clearConsole: true, // in watch mode, clears console after every build
})
  .sass('resources/js/styles/index.scss', 'public/css/app.css', {
    implementation: require('node-sass'),
  })
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
  //   files: ['resources/js/**/*']
  // });
  mix
    .sourceMaps()
    .webpackConfig({
      devtool: 'cheap-eval-source-map', // Fastest for development
    });
}

