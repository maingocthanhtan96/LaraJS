const mix = require('laravel-mix');

function publicPath(dir) {
    return path.join(__dirname, '/public', dir);
}

function resolve(dir) {
    return path.join(__dirname, '/resources/js', dir);
}

mix.js('resources/js/app.js', 'public/js')
    // .extract(['css-loader'])
    .options({
        processCssUrls: false,
        postCss:[
            require('tailwindcss')('./public/js/tailwind.config.js'),
            require('autoprefixer'),
        ]
    })
   .sass('resources/js/styles/main.scss', 'public/css')
   .sass('resources/sass/app.scss', 'public/css');
mix.webpackConfig({
    resolve: {
        extensions: ['.js', '.vue', '.json'],
        alias: {
            'vue$': 'vue/dist/vue.esm.js',
            '@': __dirname + '/resources/js'
        },
    },
    module: {
        // rules: [
        //     {
        //         test: /\.css$/,
        //         loader: ["style-loader", "css-loader"],
        //     },
        //     {
        //         test: /\.js$/,
        //         loader: path.join(__dirname, './stupidWarningFixer.js'),
        //         include: path.join(process.cwd(), './node_modules/async-validator/')
        //     }
        // ]
    }
    // output: {
    //     publicPath: '/',
    //     chunkFilename: 'js/[id].[chunkhash].js'
    // }
});

if (mix.inProduction()) {
    mix.version();
} else {
    // Development settings
    mix.sourceMaps();
    mix.webpackConfig({
            output: {
                path: publicPath('/'),
                publicPath: '/',
            },
            devtool: 'cheap-eval-source-map' // Fastest for development
        });
}


