<?php
/**
 * Custom scoper file
 *
 * PHP Version 8.1
 *
 * @package WP Sekeleton
 * @author  Bob Moore <bob@bobmoore.dev>
 * @license GPL-2.0+ <http://www.gnu.org/licenses/gpl-2.0.txt>
 * @link    https://github.com/bob-moore/WP-Plugin-Skeleton
 * @since   1.0.0
 */
declare( strict_types = 1 );
/**
 * Merge two config arrays
 *
 * @param mixed<string, mixed> $custom_array : Custom config.
 * @param mixed<string, mixed> $default_array : Default config.
 *
 * @return mixed
 */
function devkit_scoper_merge( mixed $custom_array, mixed $default_array ): mixed
{
	foreach ( $custom_array as $key => $value ) {
		if ( ! isset( $default_array[ $key ] ) ) {
			$default_array[ $key ] = $value;
		}
		elseif ( is_array( $value ) && is_array( $default_array[ $key ] ) ) {
			$default_array[ $key ] = array_merge( $value, $default_array[ $key ] );
		}
		else {
			$default_array[ $key ] = $value;
		}
	}
	return $default_array;
}
/**
 * Merge default config with custom settings.
 *
 * @param array<string, mixed> $config : Default config.
 *
 * @return array<string, mixed>
 */
function devkit_scoper_config( array $config ): array
{
	$custom = [
		'expose-global-constants' => true,
		'expose-global-classes'   => true,
		'expose-global-functions' => true,
		'exclude-functions'       => [],
		'exclude-constants'       => [],
		'exclude-classes'         => [],
		'exclude-namespaces'      => [ 'Psr' ],
	];

	return devkit_scoper_merge( $custom, $config );
}
/**
 * Merge default config with wpify settings.
 *
 * @param array<string, mixed> $config : Default config.
 *
 * @return array<string, mixed>
 */
function wpify_scoper_config( array $config ): array
{
	$wpify = include dirname( __DIR__, 1 ) . '/vendor/wpify/scoper/symbols/wordpress.php';

	$custom = [
		'exclude-functions'  => $wpify[ 'expose-functions' ] ?? [],
		'exclude-constants'  => $wpify[ 'expose-constants' ] ?? [],
		'exclude-classes'    => $wpify[ 'expose-classes' ] ?? [],
		'exclude-namespaces' => $wpify[ 'expose-namespaces' ] ?? [],
	];

	return devkit_scoper_merge( $custom, $config );
}
/**
 * Create twig patchers
 *
 * @param array<string, mixed> $patchers : array of existing patchers.
 *
 * @return array<string, mixed>
 */
function devkit_twig_patcher( array $patchers ): array
{
	include_once dirname( __DIR__, 1 ) . '/scoper/TwigPrefixer.php';

	$twigPrefixer = new TwigPrefixer();

	$patchers[] = static function ( string $filePath, string $prefix, string $contents ) use( $twigPrefixer ) {
		$twigPrefixer->setPrefix( $prefix );
		return $twigPrefixer->patchForAll( $contents );
	};

	$patchers[] = static function ( string $filePath, string $prefix, string $contents ) use( $twigPrefixer ) {
		$twigPrefixer->setPrefix( $prefix );
		return $twigPrefixer->patchForModuleNode( $contents, $filePath );
	};

	$patchers[] = static function ( string $filePath, string $prefix, string $contents ) use( $twigPrefixer ) {
		$twigPrefixer->setPrefix( $prefix );
		return $twigPrefixer->patchForCoreExtension( $contents, $filePath );
	};

	$patchers[] = static function ( string $filePath, string $prefix, string $contents ) use( $twigPrefixer ) {
		$twigPrefixer->setPrefix( $prefix );
		return $twigPrefixer->patchForEnvironment( $contents, $filePath );
	};

	return $patchers;
}
/**
 * Create PHP-DI patchers.
 * 
 * Replace private container methods with protected methods, so they can be extended.
 *
 * @param array<string, mixed> $patchers : array of existing patchers.
 *
 * @return array<string, mixed>
 */
function devkit_phpdi_patcher( array $patchers ): array
{
	$patchers[] = static function (string $filePath, string $prefix, string $content): string {
		if (str_contains( strtolower( $filePath ), strtolower( "source/vendor/php-di/php-di/src/Container.php" ) ) ) {
			$content = str_replace( 'private', 'protected', $content );
		}
		return $content;
	};

	return $patchers;
}
/**
 * Create custom scoper config.
 * 
 * Called by wpify/scoper/scoper.php
 *
 * @param array<string, mixed> $config
 *
 * @return array<string, mixed>
 */
function customize_php_scoper_config( array $config ): array
{
	/**
	 * Merge default config with custom settings.
	 */
	$config = wpify_scoper_config( $config );
	$config = devkit_scoper_config( $config );
	/**
	 * Add custom patchers.
	 */
	$config['patchers'] = devkit_twig_patcher( $config['patchers'] ?? [] );
	$config['patchers'] = devkit_phpdi_patcher( $config['patchers'] ?? [] );

	return $config;
}
