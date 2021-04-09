const path = require('path');

module.exports = {
  publicPath: '/',
  runtimeCompiler: true,
  configureWebpack: {
    resolve: {
      alias: {
        '@': path.join(__dirname, '/resources/js/'),
        '@fe': path.join(__dirname, '/frontend/src/'),
      },
      extensions: ['.js', '.vue', '.json'],
    },
  },
};
