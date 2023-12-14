module.exports = {
	plugins: [
		require( 'postcss-assets' )( {
			loadPaths: [ 'images/' ],
			relative: true,
		} ),
		require( 'autoprefixer' ),
		require( 'postcss-pxtorem' ),
	],
};
