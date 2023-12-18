<?php
/**
 * Providers Controller
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

use PLUGIN_NAMESPACE\Providers as Provider;

use PLUGIN_NAMESPACE\Deps\Devkit\WPCore,
	PLUGIN_NAMESPACE\Deps\Devkit\WPCore\DI\OnMount,
	PLUGIN_NAMESPACE\Deps\Devkit\WPCore\DI\ContainerBuilder;

/**
 * Providers controller class
 *
 * Controls and orchestrates the execution of any 3rd party providers.
 *
 * @subpackage Controllers
 */
class Providers extends WPCore\Abstracts\Mountable implements WPCore\Interfaces\Controller
{
	/**
	 * Get definitions that should be added to the service container
	 *
	 * @return array<string, mixed>
	 */
	public static function getServiceDefinitions(): array
	{
		return [
			Provider\Astra::class   => ContainerBuilder::autowire(),
			Provider\Kadence::class => ContainerBuilder::autowire(),
		];
	}
	/**
	 * Actions to perform when the class is loaded
	 *
	 * @param Provider\Astra $provider : astra provider instance.
	 *
	 * @return void
	 */
	#[OnMount]
	public function mountAstra( Provider\Astra $provider ): void
	{
		add_filter( "{$this->package}_frontend_style_dependencies", [ $provider, 'useStyles' ] );
	}
	/**
	 * Actions to perform when the class is loaded
	 *
	 * @param Provider\Kadence $provider : kadence provider instance.
	 *
	 * @return void
	 */
	#[OnMount]
	public function mountKadence( Provider\Kadence $provider ): void
	{
		add_filter( "{$this->package}_frontend_style_dependencies", [ $provider, 'useStyles' ] );
	}
}
