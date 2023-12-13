<?php
/**
 * Providers Controller
 *
 * PHP Version 8.0.28
 *
 * @package theme
 * @author  Bob Moore <bob.moore@midwestfamilymadison.com>
 * @license GPL-2.0+ <http://www.gnu.org/licenses/gpl-2.0.txt>
 * @link    https://github.com/MDMDevOps/wp-theme-boilerplate
 * @since   1.0.0
 */

namespace Mwf\Theme\Controllers;

use Mwf\Theme\Providers as Provider;

use Mwf\Theme\Deps\Mwf\WPCore,
	Mwf\Theme\Deps\Mwf\WPCore\DI\OnMount,
	Mwf\Theme\Deps\Mwf\WPCore\DI\ContainerBuilder;

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
