<?php
/**
 * StyleLoader Service Definition
 *
 * PHP Version 8.2
 *
 * @package Placeholder_Plugin
 * @author  Bob Moore <bob@bobmoore.dev>
 * @license GPL-2.0+ <http://www.gnu.org/licenses/gpl-2.0.txt>
 * @link    https://www.bobmoore.dev
 * @since   1.0.0
 */

namespace Placeholder\Plugin\Services;

use Placeholder\Plugin\ {
	Abstracts,
	Helpers
};
/**
 * Service class for style loading and management
 *
 * @subpackage Services
 */
class StyleLoader extends Abstracts\Module
{
	/**
	 * Public constructor
	 *
	 * @param UrlResolver      $url_resolver       : instance of the UrlResolver Class.
	 * @param FilePathResolver $file_path_resolver : instance of the FilePathResolver Class.
	 * @param string           $package : package name, optional.
	 */
	public function __construct( protected UrlResolver $url_resolver, protected FilePathResolver $file_path_resolver, string $package = '' )
	{
		parent::__construct( $package );
	}
	/**
	 * Build style data for registration or enqueueing
	 *
	 * @param string             $handle : handle to register.
	 * @param string             $src : relative path to css file.
	 * @param array<int, string> $dependencies : any dependencies that should be loaded first, optional.
	 * @param string|null        $version : version of CSS file, optional.
	 * @param string             $screens : what screens to register for, optional.
	 *
	 * @return array<string, mixed>|false
	 */
	protected function styleData( string $handle, string $src, array $dependencies = [], string|null $version = null, $screens = 'all' ): array|false
	{
		$file = $this->file_path_resolver->resolve( $src );

		if ( is_file( $file ) && ! filesize( $file ) ) {
			return \false;
		}

		if ( is_file( $file ) ) {
			$version = $version ?? filemtime( $file );
			$src = $this->url_resolver->resolve( $src );
		}
		$valid = str_starts_with( $src, '//' ) || filter_var( $src, \FILTER_VALIDATE_URL );
		if ( ! $valid ) {
			return \false;
		}
		return [ 'handle' => Helpers::hyphenate( $handle ), 'src' => $src, 'version' => $version, 'dependencies' => apply_filters( "{$handle}_style_dependencies", $dependencies ), 'media' => $screens ];
	}
	/**
	 * Register a CSS stylesheet with WP
	 *
	 * @param string             $handle : handle to register.
	 * @param string             $src : relative path to css file.
	 * @param array<int, string> $dependencies : any dependencies that should be loaded first, optional.
	 * @param string|null        $version : version of CSS file, optional.
	 * @param string             $screens : what screens to register for, optional.
	 *
	 * @return void
	 */
	public function register( string $handle, string $src, array $dependencies = [], string|null $version = null, string $screens = 'all' ): void
	{
		$style = $this->styleData( $handle, $src, $dependencies, $version, $screens );
		if ( ! $style ) {
			return;
		}
		wp_register_style( $style['handle'], $style['src'], $style['dependencies'], $style['version'], $style['media'] );
	}
	/**
	 * Enqueue a style in the dist/build directories
	 *
	 * @param string             $handle : handle to register.
	 * @param string             $src : relative path to css file.
	 * @param array<int, string> $dependencies : any dependencies that should be loaded first, optional.
	 * @param string|null        $version : version of CSS file, optional.
	 * @param string             $screens : what screens to register for, optional.
	 *
	 * @return void
	 */
	public function enqueue( string $handle, string $src = '', array $dependencies = [], string|null $version = null, $screens = 'all' ): void
	{
		$style = $this->styleData( $handle, $src, $dependencies, $version, $screens );
		if ( ! $style ) {
			return;
		}
		wp_enqueue_style( $style['handle'], $style['src'], $style['dependencies'], $style['version'], $style['media'] );
	}
}
