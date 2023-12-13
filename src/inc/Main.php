<?php
/**
 * Main theme handler
 *
 * PHP Version 8.0.28
 *
 * @package %package%
 * @author  %author_name% <%author_email%>
 * @license GPL-2.0+ <http://www.gnu.org/licenses/gpl-2.0.txt>
 * @link    %plugin_uri%
 * @since   1.0.0
 */

namespace %namespace%;

use %namespace%\Deps\Devkit\WPCore,
	%namespace%\Deps\Devkit\WPCore\DI\ContainerBuilder;

/**
 * Main theme file
 *
 * @subpackage Main
 */
class Theme extends WPCore\Main
{
	/**
	 * Constructor for new instance of plugin
	 *
	 * @param string $package : name of the package this instance belongs to.
	 */
	public function __construct( string $package = '' )
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
