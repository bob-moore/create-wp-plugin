const path = require( 'path' );
const wpScriptsConfig = require( '@wordpress/scripts/config/webpack.config' );
const CopyPlugin = require( 'copy-webpack-plugin' );

module.exports = () => {
	return {
		...wpScriptsConfig,
		output: {
			path: path.resolve( __dirname, '../dist/blocks' ),
		},
		devServer: {
			devMiddleware: {
				writeToDisk: true,
			},
			allowedHosts: 'auto',
			host: 'localhost',
			port: 'auto',
			proxy: {
				'/dist/blocks': {
					pathRewrite: {
						'^/dist/blocks': '',
					},
				},
			},
		},
		plugins: [
			...wpScriptsConfig.plugins,
			new CopyPlugin( {
				patterns: [
					{
						from: './**/*.twig',
						context: path.resolve( __dirname, './blocks' ),
						noErrorOnMissing: true,
					},
				],
			} ),
		],
	};
};
