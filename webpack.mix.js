const mix = require('laravel-mix');

function publicPath(dir) {
    return path.join(__dirname, '/public', dir);
}

mix.js('resources/js/app.js', 'public/js')
    .options({
        processCssUrls: false
    })
   .sass('resources/js/styles/main.scss', 'public/css');

mix.webpackConfig({
    resolve: {
        extensions: ['.js', '.vue', '.json'],
        alias: {
            '@': __dirname + '/resources/js'
        },
    },
    // output: {
    //     publicPath: '/',
    //     chunkFilename: 'js/[id].[chunkhash].js'
    // }
});

if (mix.inProduction()) {
    mix.version();
} else {
    // Development settings
    mix.webpackConfig({
            output: {
                path: publicPath('/'),
                publicPath: '/',
            },
            devtool: 'cheap-eval-source-map' // Fastest for development
        });
}


