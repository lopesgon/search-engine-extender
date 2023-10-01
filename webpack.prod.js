const { merge } = require('webpack-merge');
const common = require('./webpack.common.js');
const ReplaceInFileWebpackPlugin = require('replace-in-file-webpack-plugin');

module.exports = merge(common,
  {
    mode: 'production',
    devtool: 'source-map',
    optimization: {
      minimizer: [
        // js minification - special syntax enabling webpack 5 default terser-webpack-plugin 
        `...`
      ]
    },
    plugins: [
      new ReplaceInFileWebpackPlugin([{
        dir: 'dist',
        files: ['search-engine-extender.php'],
        rules: [{
          search: '__SEARCH_ENGINE_EXTENDER_DEVELOPMENT_MODE__',
          replace: 'false'
        }]
      }]),
    ]
  }
);