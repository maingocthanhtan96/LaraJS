const mix = require('laravel-mix');
const mergeManifest = require('./mergeManifest');

mix.extend('mergeManifest', mergeManifest);

mix.webpackConfig({
  output: {
    chunkFilename: 'js/chunks/[name].js'
  },
  resolve: {
    alias : {
      '@': path.join(__dirname, '/resources/js'),
    },
  },
});

mix.options({
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
  })
  .mergeManifest();

if (mix.inProduction()) {
  mix.version();
} else {
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

