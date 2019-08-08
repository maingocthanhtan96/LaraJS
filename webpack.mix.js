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

mix.js('resources/js/app.js', 'public/js')
    // .extract(['css-loader'])
    .options({
        processCssUrls: false,
        postCss:[
            require('tailwindcss')('./public/js/tailwind.config.js'),
            require('autoprefixer'),
        ]
    })
   // .sass('resources/js/styles/main.scss', 'public/css')
   .sass('resources/sass/app.scss', 'public/css')
   .eslint({
     fix: true,
     cache: false,
   });
mix.webpackConfig(config);

if (mix.inProduction()) {
    mix.version();
} else {
    // Development settings
    // mix.browserSync({
    //   proxy: process.env.APP_URL,
    //   files: ['resources/js/**/*']
    // });
    mix.sourceMaps();
    mix.webpackConfig({
            output: {
                path: publicPath('/'),
                publicPath: '/',
            },
            devtool: 'cheap-eval-source-map' // Fastest for development
        });
}


