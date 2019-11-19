let mix = require('laravel-mix');
let ManifestPlugin = require('laravel-mix/src/webpackPlugins/ManifestPlugin');
let merge = require('lodash').merge;

/*
 * Because we compile css and js file in 2 seperated command,
 * so to prevent mix-manifest.json is overwrited by follow occured command, this function help to merge it all.
*/

module.exports = (config) => {
  config.plugins.forEach((plugin, index) => {
    if (plugin instanceof ManifestPlugin) {
      config.plugins.splice(index, 1);
    }
  });

  config.plugins.push(new class {
    apply(compiler) {

      compiler.plugin('emit', (curCompiler, callback) => {
        let stats = curCompiler.getStats().toJson();

        try {
          Mix.manifest.manifest = merge(Mix.manifest.read(), Mix.manifest.manifest);
        } catch (e) {

        }

        Mix.manifest.transform(stats).refresh();
        callback();
      });
    }
  })
};
