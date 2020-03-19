const mix = require('laravel-mix');
const exec = require('child_process').exec;
const tailwindcss = require('tailwindcss');
const mergeManifest = require('./mergeManifest');
require('laravel-mix-eslint');
/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

const glob = require('glob');
const path = require('path');

/*
 |--------------------------------------------------------------------------
 | Vendor assets
 |--------------------------------------------------------------------------
 */

function mixAssetsDir(query, cb) {
  (glob.sync('resources/frontend/' + query) || []).forEach(f => {
    f = f.replace(/[\\\/]+/g, '/');
    cb(f, f.replace('resources', 'public'));
  });
}

const sassOptions = {
  precision: 5,
};

mix.extend('mergeManifest', mergeManifest);

// plugins Core stylesheets
mixAssetsDir('sass/plugins/**/!(_)*.scss', (src, dest) =>
  mix.sass(
    src,
    dest.replace(/(\\|\/)sass(\\|\/)/, '$1css$2').replace(/\.scss$/, '.css'),
    sassOptions
  )
);

// themes Core stylesheets
mixAssetsDir('sass/themes/**/!(_)*.scss', (src, dest) =>
  mix.sass(
    src,
    dest.replace(/(\\|\/)sass(\\|\/)/, '$1css$2').replace(/\.scss$/, '.css'),
    sassOptions
  )
);

// pages Core stylesheets
mixAssetsDir('sass/pages/**/!(_)*.scss', (src, dest) =>
  mix.sass(
    src,
    dest.replace(/(\\|\/)sass(\\|\/)/, '$1css$2').replace(/\.scss$/, '.css'),
    sassOptions
  )
);

// Core stylesheets
mixAssetsDir('sass/core/**/!(_)*.scss', (src, dest) =>
  mix.sass(
    src,
    dest.replace(/(\\|\/)sass(\\|\/)/, '$1css$2').replace(/\.scss$/, '.css'),
    sassOptions
  )
);

// script js
mixAssetsDir('js/scripts/**/*.js', (src, dest) => mix.scripts(src, dest));

/*
 |--------------------------------------------------------------------------
 | Application assets
 |--------------------------------------------------------------------------
 */

mixAssetsDir('vendors/js/**/*.js', (src, dest) => mix.scripts(src, dest));
mixAssetsDir('vendors/css/**/*.css', (src, dest) => mix.copy(src, dest));
mixAssetsDir('vendors/css/editors/quill/fonts/', (src, dest) =>
  mix.copy(src, dest)
);
mix.copyDirectory('resources/frontend/images', 'public/frontend/images');
mix.copyDirectory('resources/frontend/fonts', 'public/frontend/fonts');

mix
  .js('resources/frontend/js/core/app-menu.js', 'public/frontend/js/core')
  .js('resources/frontend/js/core/app.js', 'public/frontend/js/core')
  .sass('resources/frontend/sass/bootstrap.scss', 'public/frontend/css')
  .sass(
    'resources/frontend/sass/bootstrap-extended.scss',
    'public/frontend/css'
  )
  .sass('resources/frontend/sass/colors.scss', 'public/frontend/css')
  .sass('resources/frontend/sass/components.scss', 'public/frontend/css')
  .sass('resources/frontend/sass/custom-rtl.scss', 'public/frontend/css')
  .sass('resources/frontend/sass/custom-laravel.scss', 'public/frontend/css')
  .sass('resources/frontend/tailwind/tailwind.scss', 'public/frontend/css')
  .options({
    processCssUrls: false,
    postCss: [
      tailwindcss('resources/frontend/tailwind/tailwind.js'),
      require('autoprefixer'),
    ],
  })
  .mergeManifest();

mix.then(() => {
  if (process.env.MIX_CONTENT_DIRECTION === 'rtl') {
    const command = `node ${path.resolve(
      'node_modules/rtlcss/bin/rtlcss.js'
    )} -d -e ".css" ./public/frontend/css/ ./public/frontend/css/`;
    exec(command, function(err, stdout, stderr) {
      if (err !== null) {
        console.log(err);
      }
    });
    // exec('./node_modules/rtlcss/bin/rtlcss.js -d -e ".css" ./public/css/ ./public/css/');
  }
});

if (mix.inProduction()) {
  mix.version();
} else {
  mix.sourceMaps().webpackConfig({
    devtool: 'cheap-eval-source-map', // Fastest for development
  });
}
