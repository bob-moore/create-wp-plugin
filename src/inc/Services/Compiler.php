<?php
/**
 * Compiler Service Definition
 *
 * PHP Version 8.0.28
 *
 * @package Devkit_WP_Framework
 * @author  Bob Moore <bob@bobmoore.dev>
 * @license GPL-2.0+ <http://www.gnu.org/licenses/gpl-2.0.txt>
 * @link    https://github.com/bob-moore/wp-framework-core
 * @since   1.0.0
 */

namespace PLUGIN_NAMESPACE\Services;

use PLUGIN_NAMESPACE\Deps\Devkit\WPCore;

use PLUGIN_NAMESPACE\Deps\Timber\Timber,
	PLUGIN_NAMESPACE\Deps\Timber\Loader;

use PLUGIN_NAMESPACE\Deps\Twig\Error\SyntaxError;

use PLUGIN_NAMESPACE\Deps\DI\Attribute\Inject;

/**
 * Service class to compile twig files and provide timber functions
 * - Add twig functions & filters
 * - Define template locations
 * - Filter timber context
 * - Add render and compile functions
 *
 * @subpackage Services
 */
class Compiler extends WPCore\Abstracts\Mountable implements WPCore\Interfaces\Services\Compiler, WPCore\Interfaces\Handlers\Directory
{
	use WPCore\Traits\Handlers\Directory;
	use WPCore\Traits\Handlers\Environment;

	/**
	 * Cached template locations for timber to search for templates
	 *
	 * @var array<string>
	 */
	protected array $template_locations = [];
	/**
	 * Directory path to twig view files
	 *
	 * @var string
	 */
	protected string $views_dir = '';
	/**
	 * Directory path to assets
	 *
	 * @var string
	 */
	protected string $assets_dir = '';
	/**
	 * Setter for the views directory
	 *
	 * @param string $views_dir : path to twig files directory.
	 *
	 * @return void
	 */
	#[Inject]
	public function setViewsDirectory( #[Inject( 'config.views.dir' )] string $views_dir ): void
	{
		$this->views_dir = $views_dir;
	}
	/**
	 * Setter for the assets directory
	 *
	 * @param string $asset_dir : path to assets directory.
	 *
	 * @return void
	 */
	#[Inject]
	public function setAssetsDirectory( #[Inject( 'config.assets.dir' )] string $asset_dir ): void
	{
		$this->assets_dir = $asset_dir;
	}
	/**
	 * Add the 'post' to context, if not already present.
	 *
	 * @param array<string, mixed> $context : optional context to merge.
	 *
	 * @return array<int, string>
	 */
	public function context( array $context = [] ): array
	{
		$context = array_merge( Timber::context(), $context );
		return $context;
	}
	/**
	 * Filters the default locations array for twig to search for templates. We never use some paths, so there's
	 * no reason to waste overhead looking for templates there.
	 *
	 * @param array<string,mixed> $locations : Array of absolute paths to
	 *                                        available templates.
	 *
	 * @return array<string,mixed> $locations
	 */
	public function templateLocations( array $locations ): array
	{
		if ( empty( $this->template_locations ) ) {
			$this->template_locations = array_map( [ $this, 'filterTemplateLocations' ], $locations );

			$this->views_dir = apply_filters(
				"{$this->package}_views_directory",
				$this->views_dir
			);

			$package_template_directories = [
				trailingslashit( get_stylesheet_directory() . '/' . $this->views_dir ),
				trailingslashit( get_template_directory() . '/' . $this->views_dir ),
				trailingslashit( $this->dir( $this->assets_dir ) ),
				trailingslashit( $this->dir( $this->views_dir ) ),
			];

			$package_template_directories = apply_filters(
				"{$this->package}_views_directories",
				$package_template_directories
			);

			$this->template_locations[ $this->package ] = array_unique(
				array_filter(
					$package_template_directories,
					function ( $dir ) {
						return is_dir( $dir );
					}
				)
			);
		}
		return $this->template_locations;
	}
	/**
	 * Recursive function to remove library locations from twig search
	 *
	 * @param string|array<string> $location : array of template locations for twig to search in.
	 *
	 * @return boolean|array<string>|string
	 */
	protected function filterTemplateLocations( string|array $location ): bool|array|string
	{
		if ( is_array( $location ) ) {
			$filtered_locations = array_filter(
				$location,
				function ( $template_location ) {
					return $this->filterTemplateLocations( $template_location );
				}
			);
			return $filtered_locations;
		} elseif ( is_string( $location ) ) {
			return str_contains( $location, __DIR__ ) ? false : $location;
		}
	}
	/**
	 * Compile a twig/html template file using Timber
	 *
	 * @param string|array<int, string> $template_file : relative path to template file.
	 * @param array<string, mixed>      $context : additional context to pass to twig.
	 *
	 * @return string
	 */
	public function compile( $template_file, array $context = [] ): string
	{
		try {
			$template_file = is_array( $template_file ) ? $template_file : [ $template_file ];

			ob_start();

			Timber::render( $template_file, $this->context( $context ), 600, Loader::CACHE_NONE );

			$contents = ob_get_contents();

			return apply_filters( "{$this->package}_compiled", $contents );
		} catch ( SyntaxError $e ) {
			return $this->isDev() ? esc_html( $e->getMessage() ) : '';
		} finally {
			ob_end_clean();
		}
	}
	/**
	 * Compile a string with timber/twig
	 *
	 * @param string               $content : string content to compile.
	 * @param array<string, mixed> $context : additional context to pass to twig.
	 *
	 * @return string
	 */
	public function compileString( string $content, array $context = [] ): string
	{
		try {
			ob_start();

			Timber::render_string( $content, $this->context( $context ) );

			return apply_filters( "{$this->package}_compiled", ob_get_contents() );
		} catch ( SyntaxError $e ) {
			return $this->isDev() ? esc_html( $e->getMessage() ) : '';
		} finally {
			ob_end_clean();
		}
	}
	/**
	 * Render a frontend twig template with timber/twig
	 *
	 * Wrapper for `compile` but outputs the content instead of returning it
	 * Ignored by PHPCS because we cannot escape at this time. Values should be
	 * escaped at the template level.
	 *
	 * @param string|array<int, string> $template_file : file to render.
	 * @param array<string, mixed>      $context : additional context to pass to twig.
	 *
	 * @return void
	 */
	public function render( $template_file, array $context = [] ): void
	{
		// phpcs:ignore
		echo $this->compile( $template_file, $context );
	}
	/**
	 * Render a string with timber/twig
	 *
	 * Wrapper for `compileString` but outputs the content instead of returning it
	 * Ignored by PHPCS because we cannot escape at this time. Values should be
	 * escaped at the template level.
	 *
	 * @param string               $content : string content to compile.
	 * @param array<string, mixed> $context : additional context to pass to twig.
	 *
	 * @return void
	 */
	public function renderString( string $content, array $context = [] ): void
	{
		// phpcs:ignore
		echo $this->compileString( $content, $context );
	}
}
