<?php
/**
 * Handler Controller
 *
 * PHP Version 8.0.28
 *
 * @package PLUGIN_SLUG
 * @author  AUTHOR_NAME <AUTHOR_EMAIL>
 * @license GPL-2.0+ <http://www.gnu.org/licenses/gpl-2.0.txt>
 * @link    https://github.com/bob-moore/Devkit-Plugin-Boilerplate
 * @since   1.0.0
 */

namespace PLUGIN_NAMESPACE\Controllers;

use PLUGIN_NAMESPACE\Handlers as Handler;

use PLUGIN_NAMESPACE\Deps\Devkit\WPCore,
	PLUGIN_NAMESPACE\Deps\Devkit\WPCore\DI\OnMount,
	PLUGIN_NAMESPACE\Deps\Devkit\WPCore\DI\ContainerBuilder;

/**
 * Handler controller class
 *
 * Controls and orchestrates the execution of specific handlers.
 *
 * @subpackage Controllers
 */
class Handlers extends WPCore\Controllers\Handlers
{
	/**
	 * Get definitions that should be added to the service container
	 *
	 * @return array<string, mixed>
	 */
	public static function getServiceDefinitions(): array
	{
		return array_merge( parent::getServiceDefinitions(), [
			Handler\Editor::class => ContainerBuilder::autowire(),
			Handler\Images::class => ContainerBuilder::autowire(),
		] );
	}
	/**
	 * Actions to perform when the class is loaded
	 *
	 * @param Handler\Editor $handler : instance of editor service.
	 *
	 * @return void
	 */
	#[OnMount]
	public function mountEditor( Handler\Editor $handler ): void
	{
		add_action( 'after_setup_theme', [ $handler, 'themeSupport' ] );
		add_action( 'after_setup_theme', [ $handler, 'editorStylesheet' ], 99999 );
	}
	/**
	 * Actions to work with images
	 *
	 * @param Handler\Images $handler : instance of images handler.
	 *
	 * @return void
	 */
	#[OnMount]
	public function mountIMages( Handler\Images $handler ): void
	{
		add_filter( 'after_setup_theme', [ $handler, 'addImageSizes' ] );
		add_filter( 'image_size_names_choose', [ $handler, 'addImageSizeLabels' ] );
	}
}
