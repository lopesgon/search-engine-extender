const { merge } = require('webpack-merge');
const common = require('./webpack.common.js');
const BrowserSyncPlugin = require('browser-sync-webpack-plugin');
const ReplaceInFileWebpackPlugin = require('replace-in-file-webpack-plugin');

const localDomain = 'http://localhost:8090';

module.exports = merge(common,
  {
    mode: 'development',
    devtool: 'inline-source-map',
    watchOptions: {
      ignored: '/node_modules/',
      aggregateTimeout: 300,
      poll: 1000,
    },
    plugins: [
      new ReplaceInFileWebpackPlugin([{
        dir: 'dist',
        files: ['search-engine-extender.php'],
        rules: [{
          search: '__SEARCH_ENGINE_EXTENDER_DEVELOPMENT_MODE__',
          replace: 'true'
        }]
      }]),
      new BrowserSyncPlugin({
        host: 'localhost',
        port: 3000,
        proxy: localDomain,
        browser: 'google chrome',
        open: false,
        files: [
          {
            match: [
              './dist/**/*.php',
              './dist/**/*.scss',
              './dist/**/*.js',
              // Add other file types if needed
            ],
            fn: function (event, file) {
              if (event === 'change' || event === 'create') {
                const bs = require('browser-sync').get('bs-webpack-plugin');
                bs.reload();
              }
            },
          },
        ],
      }, {
        // Prevent BrowserSync from reloading the page and let Webpack Dev Server take care of this
        reload: true
      }),
    ]
  }
);