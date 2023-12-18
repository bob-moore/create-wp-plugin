<?php
/**
 * Main theme handler
 *
 * PHP Version 8.0.28
 *
 * @package PLUGIN_SLUG
 * @author  AUTHOR_NAME <AUTHOR_EMAIL>
 * @license GPL-2.0+ <http://www.gnu.org/licenses/gpl-2.0.txt>
 * @link    https://github.com/bob-moore/Devkit-Plugin-Boilerplate
 * @since   1.0.0
 */

namespace PLUGIN_NAMESPACE;

use PLUGIN_NAMESPACE\Deps\Devkit\WPCore,
	PLUGIN_NAMESPACE\Deps\Devkit\WPCore\DI\ContainerBuilder;

/**
 * Main file
 *
 * @subpackage Main
 */
class Main extends WPCore\Main
{
	/**
	 * Constructor for new instance of plugin
	 *
	 * @param string $package : name of the package this instance belongs to.
	 * @param string $root_file : path to root file of the plugin.
	 *
	 * @throws ValueError Error thrown if required data not provided.
	 */
	public function __construct( string $package = '', string $root_file = '' )
	{
		$this->setPackage( $package );
		$this->setUrl( get_stylesheet_directory_uri() );
		$this->setDir( get_stylesheet_directory() );
		parent::__construct();
	}
	/**
	 * Get service definitions to add to service container
	 *
	 * @return array<string, mixed>
	 */
	protected function getServiceDefinitions(): array
	{
		$definitions = [
			Controllers\Handlers::class  => ContainerBuilder::autowire(),
			Controllers\Router::class    => ContainerBuilder::autowire(),
			Controllers\Providers::class => ContainerBuilder::autowire(),
		];
		return array_merge( $definitions, parent::getServiceDefinitions() );
	}
	/**
	 * Instantiate controllers
	 *
	 * @return void
	 */
	public function mount(): void
	{
		$this->service_container->get( Controllers\Handlers::class );
		$this->service_container->get( Controllers\Router::class );
		$this->service_container->get( Controllers\Providers::class );

		parent::mount();
	}
}
