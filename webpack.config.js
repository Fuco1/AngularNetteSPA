var ExtractTextPlugin = require('extract-text-webpack-plugin');
var HtmlWebpackPlugin = require('html-webpack-plugin');
var path = require('path');

module.exports = {
    entry: [
        './www/js/main.js',
        './node_modules/ng-admin/build/ng-admin.min.css',
    ],
    output: {
        path: __dirname + "/www/build",
        filename: "main.js",
    },
    module: {
        loaders: [
            { test: /\.js$/, loader: 'babel' },
            { test: /\.html$/, loader: 'html' },
            { test: /\.(woff2?|svg|ttf|eot)(\?.*)?$/, loader: 'url' },
            { test: /\.css$/, loader: ExtractTextPlugin.extract('css') },
            { test: /\.scss$/, loader: ExtractTextPlugin.extract('css!sass') }
        ],
    },
    plugins: [
        new ExtractTextPlugin('[name].css', {
            allChunks: true
        }),
        new HtmlWebpackPlugin({
            template: path.join(__dirname, 'www/index.html'),
            inject: true,
            hash: true,
            filename: 'index.html',
        }),
    ],
    externals: {
        'text-encoding': 'window'
    },
	devServer: {
		port: 8912
	}
};
