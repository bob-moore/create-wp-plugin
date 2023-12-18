const path = require( 'path' );
const wpScriptsConfig = require( '@wordpress/scripts/config/webpack.config' );
const MiniCssExtractPlugin = require( 'mini-css-extract-plugin' );
const { CleanWebpackPlugin } = require( 'clean-webpack-plugin' );
const plugins = wpScriptsConfig.plugins.filter( ( item ) => {
	return ! [
		'CopyPlugin',
		'MiniCssExtractPlugin',
		'CleanWebpackPlugin',
	].includes( item.constructor.name );
} );

module.exports = {
	...wpScriptsConfig,
	entry: {
		frontend: [
			path.resolve( __dirname, 'frontend', 'index.ts' ),
			path.resolve( __dirname, 'frontend', 'index.scss' ),
		],
		admin: [
			path.resolve( __dirname, 'admin', 'index.ts' ),
			path.resolve( __dirname, 'admin', 'index.scss' ),
		],
		login: [
			path.resolve( __dirname, 'login', 'index.ts' ),
			path.resolve( __dirname, 'login', 'index.scss' ),
		],
	},
	output: {
		path: path.resolve( __dirname, '../dist' ),
		filename: '[name]/bundle.js',
	},
	devServer: {
		devMiddleware: {
			writeToDisk: true,
		},
		allowedHosts: 'auto',
		host: 'localhost',
		port: 'auto',
		proxy: {
			'/dist': {
				pathRewrite: {
					'^/dist': '',
				},
			},
		},
	},
	plugins: [
		...plugins,
		new CleanWebpackPlugin( {
			cleanOnceBeforeBuildPatterns: [
				'*.hot-update.*',
				'**/*.hot-update.*',
				'!images/**',
				'!fonts/**',
				'!acf/**',
			],
			dry: false,
		} ),
		new MiniCssExtractPlugin( { filename: '[name]/bundle.css' } ),
	],
};
